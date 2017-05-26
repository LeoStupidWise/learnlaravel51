<?php
/**
 * Created by PhpStorm.
 * User: Zoe
 * Date: 2017/3/27
 * Time: 12:34
 */

// 编码

/**
 * Class RedisLock
 *
 * 设计思路：我们构造锁的目的就是在高并发下消除选择竞争、保持数据一致性，构造锁的时候，我们需要注意几个问题：
 *      1、预防处理持有锁在执行操作的时候进程奔溃，导致死锁，其他进程一直得不到此锁
 *      2、持有锁进程因为操作时间长而导致锁自动释放，但本身进程并不知道，最后错误的释放其他进程的锁
 *      3、一个进程锁过期后，其他多个进程同时尝试获取锁，并且都成功获得锁
 */

class RedisLock
{
    // redis分布式锁
    private $redis = '';        # 存储redis对象

    /**
     * RedisLock constructor.
     *
     * @param $host     |  redis主机地址
     * @param int $port |  端口
     */
    public function __construct($host, $port = 6379)
    {
        $this->redis = new Redis();
        $this->redis->connect($host, $port);
    }

    /**
     * @desc 加锁方法
     *
     * @param $lock_name    |  锁的名字
     * @param int $timeout  |  锁的过期时间
     *
     * @return string       |  成功返回identifier/失败返回false
     */
    public function getLock($lock_name, $timeout = 2)
    {
        $identifier   =  uniqid();            # 获取唯一标识符
        $timeout      =  ceil($timeout);      # 确保是整数
        $end          =  time() + $timeout;
        while (time() < $end)
        {
            if ($this->redis->setnx($lock_name, $identifier)) {    # 查看lock_name是否被上锁
                $this->redis->expire($lock_name, $timeout);        # 为lock_name设置过期时间，防止死锁
                return $identifier;                                # 返回一维标识符
            } elseif ($this->redis->ttl($lock_name) === -1) {
                $this->redis->expire($lock_name, $timeout);        # 检测是否设置过期时间，没有则加上，可能客户端A在上一步进程就崩溃了，客户端B就能检测出来，并设置过期时间
            }
            usleep(0.001);                                          # 停止0.001秒
        }
    }

    /**
     * @desc 释放锁
     *
     * @param $lock_name    |  锁名
     * @param $identifier   |  锁的唯一值
     *
     * @return bool
     */
    public function releaseLock($lock_name, $identifier)
    {
        if ($this->redis->get($lock_name) == $identifier) {
            $this->redis->multi();
            $this->redis->del($lock_name);          # 释放锁
            $this->redis->exec();
            return true;
        } else {
            return false;                           # 其他客户端修改了锁，不能删除别人的所
        }
    }

    /**
     * @desc 测试
     *
     * @param $lock_name    |  锁的名字
     */
    public function test($lock_name)
    {
        $start          =  time();
        for ($i = 0; $i < 10000; $i++) {
            $identifier =  $this->getLock($lock_name);
            if ($identifier) {
                $count  =  $this->redis->get("count");
                $count  =  $count + 1;
                $this->redis->set("count", $count);
                $this->releaseLock($lock_name, $identifier);
            }
        }
        $end            =  time();
        echo "This is OK<br/>";
        echo "执行时间为：".($end - $start);
    }
}