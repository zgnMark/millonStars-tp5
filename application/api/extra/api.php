<?php
/**
 * api地址
 */
return [
    //--------------------------------直播API-------------------------------
    //创建频道
    'app.channel.create'          => 'https://vcloud.163.com/app/channel/create',
    //修改频道
    'app.channel.update'          => 'https://vcloud.163.com/app/channel/update',
    //删除频道
    'app.channel.delete'          => 'https://vcloud.163.com/app/channel/delete',
    //获取频道状态
    'app.channelstats'            => 'https://vcloud.163.com/app/channelstats',
    //获取频道列表
    'app.channellist'             => 'https://vcloud.163.com/app/channellist',
    //重新获取推流地址
    'app.address'                 => 'https://vcloud.163.com/app/address',
    //设置频道为录制状态
    'app.channel.setAlwaysRecord' => 'https://vcloud.163.com/app/channel/setAlwaysRecord',
    //禁用频道
    'app.channel.pause'           => 'https://vcloud.163.com/app/channel/pause',
    //批量禁用频道
    'app.channellist.pause'       => 'https://vcloud.163.com/app/channellist/pause',
    //恢复频道
    'app.channel.resume'          => 'https://vcloud.163.com/app/channel/resume',
    //批量恢复频道
    'app.channellist.resume'      => 'https://vcloud.163.com/app/channellist/resume',
    //获取录制视频文件列表
    'app.videolist'               => 'https://vcloud.163.com/app/videolist',
    //获取某一时间范围的录制视频文件列表
    'app.vodvideolist'            => 'https://vcloud.163.com/app/vodvideolist',
    //设置视频录制回调地址
    'app.record.setcallback'      => 'https://vcloud.163.com/app/record/setcallback',
    //设置回调的加签秘钥
    'app.callback.setSignKey'     => 'https://vcloud.163.com/app/callback/setSignKey',
    //录制文件合并
    'app.video.merge'             => 'https://vcloud.163.com/app/video/merge',
    //录制重置
    'app.channel.resetRecord'     => 'https://vcloud.163.com/app/channel/resetRecord',
    //直播实时转码地址
    'app.transcodeAddress'        => 'https://vcloud.163.com/app/transcodeAddress',
    //视频录制回调地址查询
    'app.record.callbackQuery'    => 'https://vcloud.163.com/app/record/callbackQuery',
    //设置录制信息
    'app.channel.setupRecordInfo' => 'https://vcloud.163.com/app/channel/setupRecordInfo',
    //--------------------------------直播API结束-------------------------------

    //--------------------------------IM-api------------------------------------
    //创建网易云通信ID
    'nimserver.user.create'       => 'https://api.netease.im/nimserver/user/create.action',

    //--------------------------------IM-api结束--------------------------------

];
