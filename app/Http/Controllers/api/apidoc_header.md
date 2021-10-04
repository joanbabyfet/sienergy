[TOC]

## 域名

内网测试|线上测试|线上正式
--------|--------|--------|
http://api1.sienergy.com/|http://testapi.sienergy.com/|http://api.sienergy.com/

## http头部参数(仅限 app)

参数名|参数類型|参数说明
--------|--------|--------|
Authorization|String|认证token
os|String|客户端系统信息，ios 12/android 7.0/h5
timezone|String|客户端时区，格式 GMT-8/UTC-8
language|String|language 客户端语言，格式 zh-cn/en/km
version|String|客户端当前版本号，格式 x.x.x
device|String|客户端设备信息，如：mei=设备IMEI值\|pixel=12*32（设备分辨率）

## 第三方应用请求接口说明

#### 注意事项
1. 所有请求使用 `POST` 提交
2. 所有接口需要进行参数签名，签名字段：sign
3. sign 参数不参与签名

#### 签名密钥

*XX项目应用*

-|内网测试|线上测试|线上正式
--------|--------|--------|--------|
app_id|ac8dee213669c167a52e928e50b44eb4|-|-|
app_key|T3W975LBSOVK0RKXXWFU2JPHWLMJ2J19|-|-|

#### 必传参数

参数名|参数類型|参数说明
--------|--------|--------|
app_id|String|应用id
sign|String|签名
uid|String|对接人uid
os|String|客户端系统信息，ios 12/android 7.0/h5
timezone|String|客户端时区，格式 GMT-8/UTC-8
language|String|language 客户端语言，格式 zh-cn/en/km
version|String|客户端当前版本号，格式 x.x.x
device|String|客户端设备信息，如：mei=设备IMEI值\|pixel=12*32（设备分辨率）

#### 签名算法
```
1. 参数正排序
2. 使用&连接参数生成签名字符串
3. 签名字符串后面加上密钥参数&key=[app_key]
4. 把签名字符串md5加密再转大写生成签名
```

## h5客户端请求接口说明

#### 注意事项
1. 所有请求使用 `POST` 提交
2. 由于h5客户端有些接口不需要登录就能访问，为了增加安全性不需要登录的接口需要进行参数签名，签名字段：sign
3. sign 参数不参与签名

#### 签名密钥

-|内网测试|线上测试|线上正式
--------|--------|--------|--------|
app_key|19A1D386E03B4FAB099260708AE46229|LMFJUYWYRM10C7E95FSG9ZK55TWH9WM4|SLLQV6OO89OX3SVZF07X3L0UCAY2XTOE

#### 签名算法
```
1、参数正排序
2、使用&连接参数生成签名字符串
3、签名字符串后面加上密钥参数&key=[app_key]
4、把签名字符串md5加密再转大写生成签名
```

## socket说明
#### 域名

终端類型|内网测试|线上测试|线上正式
--------|--------|--------|--------|
App|192.168.10.48:9601|ssl::testclient.sienergy.com:9601|ssl::client.sienergy.com:9601
Web|ws://192.168.10.47:9901|wss://testclient.sienergy.com:9901|ssl://client.sienergy.com:9901

#### 心跳
*在10秒内未向服务器端发送数据，将会被切断*

#### socket发送格式
```
{
	"action": "xxx",  //字符串
	"token": "xxx",  //登录返回的token
	"data": []/{} //数据，数组/对象
}
```

#### socket数据接收格式
```
{
	"action": "xxx",  //字符串
	"code": 0, // 0=成功 -1=失败 4001=未登录或登录超时
	"msg": "xxx", //信息
	"order_version":x, //int 当前订单版本号，用于对比本地版本号控制拉单
	"data": []/{} //数据，数组/对象
}
```

## api版本更新说明

### 3.6.0版本
#### app接口
- 提交订单接口改成 `?ct=order&ac=post_order` 增加 `pay_type` 必填参数
#### H5接口
- 增加 `?ct=coupon&ac=get_activity_11_coupon` 领取双十一优惠券接口
- 增加 `?ct=coupon&ac=check_activity_11_coupon` 是否领取双十一优惠券接口
