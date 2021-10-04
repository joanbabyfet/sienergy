define({
  "name": "鑫盈能源api接口文檔",
  "version": "1.0.0",
  "description": "鑫盈能源api接口文檔",
  "title": "鑫盈能源api接口文檔",
  "url": "/",
  "sampleUrl": null,
  "header": {
    "title": "接口公共说明",
    "content": "<p>[TOC]</p>\n<h2>域名</h2>\n<table>\n<thead>\n<tr>\n<th>内网测试</th>\n<th>线上测试</th>\n<th>线上正式</th>\n</tr>\n</thead>\n<tbody>\n<tr>\n<td>http://api1.sienergy.com/</td>\n<td>http://testapi.sienergy.com/</td>\n<td>http://api.sienergy.com/</td>\n</tr>\n</tbody>\n</table>\n<h2>http头部参数(仅限 app)</h2>\n<table>\n<thead>\n<tr>\n<th>参数名</th>\n<th>参数類型</th>\n<th>参数说明</th>\n</tr>\n</thead>\n<tbody>\n<tr>\n<td>Authorization</td>\n<td>String</td>\n<td>认证token</td>\n</tr>\n<tr>\n<td>os</td>\n<td>String</td>\n<td>客户端系统信息，ios 12/android 7.0/h5</td>\n</tr>\n<tr>\n<td>timezone</td>\n<td>String</td>\n<td>客户端时区，格式 GMT-8/UTC-8</td>\n</tr>\n<tr>\n<td>language</td>\n<td>String</td>\n<td>language 客户端语言，格式 zh-cn/en/km</td>\n</tr>\n<tr>\n<td>version</td>\n<td>String</td>\n<td>客户端当前版本号，格式 x.x.x</td>\n</tr>\n<tr>\n<td>device</td>\n<td>String</td>\n<td>客户端设备信息，如：mei=设备IMEI值|pixel=12*32（设备分辨率）</td>\n</tr>\n</tbody>\n</table>\n<h2>第三方应用请求接口说明</h2>\n<h4>注意事项</h4>\n<ol>\n<li>所有请求使用 <code>POST</code> 提交</li>\n<li>所有接口需要进行参数签名，签名字段：sign</li>\n<li>sign 参数不参与签名</li>\n</ol>\n<h4>签名密钥</h4>\n<p><em>XX项目应用</em></p>\n<table>\n<thead>\n<tr>\n<th>-</th>\n<th>内网测试</th>\n<th>线上测试</th>\n<th>线上正式</th>\n</tr>\n</thead>\n<tbody>\n<tr>\n<td>app_id</td>\n<td>ac8dee213669c167a52e928e50b44eb4</td>\n<td>-</td>\n<td>-</td>\n</tr>\n<tr>\n<td>app_key</td>\n<td>T3W975LBSOVK0RKXXWFU2JPHWLMJ2J19</td>\n<td>-</td>\n<td>-</td>\n</tr>\n</tbody>\n</table>\n<h4>必传参数</h4>\n<table>\n<thead>\n<tr>\n<th>参数名</th>\n<th>参数類型</th>\n<th>参数说明</th>\n</tr>\n</thead>\n<tbody>\n<tr>\n<td>app_id</td>\n<td>String</td>\n<td>应用id</td>\n</tr>\n<tr>\n<td>sign</td>\n<td>String</td>\n<td>签名</td>\n</tr>\n<tr>\n<td>uid</td>\n<td>String</td>\n<td>对接人uid</td>\n</tr>\n<tr>\n<td>os</td>\n<td>String</td>\n<td>客户端系统信息，ios 12/android 7.0/h5</td>\n</tr>\n<tr>\n<td>timezone</td>\n<td>String</td>\n<td>客户端时区，格式 GMT-8/UTC-8</td>\n</tr>\n<tr>\n<td>language</td>\n<td>String</td>\n<td>language 客户端语言，格式 zh-cn/en/km</td>\n</tr>\n<tr>\n<td>version</td>\n<td>String</td>\n<td>客户端当前版本号，格式 x.x.x</td>\n</tr>\n<tr>\n<td>device</td>\n<td>String</td>\n<td>客户端设备信息，如：mei=设备IMEI值|pixel=12*32（设备分辨率）</td>\n</tr>\n</tbody>\n</table>\n<h4>签名算法</h4>\n<pre class=\"prettyprint\">1. 参数正排序\n2. 使用&连接参数生成签名字符串\n3. 签名字符串后面加上密钥参数&key=[app_key]\n4. 把签名字符串md5加密再转大写生成签名\n</code></pre>\n<h2>h5客户端请求接口说明</h2>\n<h4>注意事项</h4>\n<ol>\n<li>所有请求使用 <code>POST</code> 提交</li>\n<li>由于h5客户端有些接口不需要登录就能访问，为了增加安全性不需要登录的接口需要进行参数签名，签名字段：sign</li>\n<li>sign 参数不参与签名</li>\n</ol>\n<h4>签名密钥</h4>\n<table>\n<thead>\n<tr>\n<th>-</th>\n<th>内网测试</th>\n<th>线上测试</th>\n<th>线上正式</th>\n</tr>\n</thead>\n<tbody>\n<tr>\n<td>app_key</td>\n<td>19A1D386E03B4FAB099260708AE46229</td>\n<td>LMFJUYWYRM10C7E95FSG9ZK55TWH9WM4</td>\n<td>SLLQV6OO89OX3SVZF07X3L0UCAY2XTOE</td>\n</tr>\n</tbody>\n</table>\n<h4>签名算法</h4>\n<pre class=\"prettyprint\">1、参数正排序\n2、使用&连接参数生成签名字符串\n3、签名字符串后面加上密钥参数&key=[app_key]\n4、把签名字符串md5加密再转大写生成签名\n</code></pre>\n<h2>socket说明</h2>\n<h4>域名</h4>\n<table>\n<thead>\n<tr>\n<th>终端類型</th>\n<th>内网测试</th>\n<th>线上测试</th>\n<th>线上正式</th>\n</tr>\n</thead>\n<tbody>\n<tr>\n<td>App</td>\n<td>192.168.10.48:9601</td>\n<td>ssl::testclient.sienergy.com:9601</td>\n<td>ssl::client.sienergy.com:9601</td>\n</tr>\n<tr>\n<td>Web</td>\n<td>ws://192.168.10.47:9901</td>\n<td>wss://testclient.sienergy.com:9901</td>\n<td>ssl://client.sienergy.com:9901</td>\n</tr>\n</tbody>\n</table>\n<h4>心跳</h4>\n<p><em>在10秒内未向服务器端发送数据，将会被切断</em></p>\n<h4>socket发送格式</h4>\n<pre class=\"prettyprint\">{\n\t\"action\": \"xxx\",  //字符串\n\t\"token\": \"xxx\",  //登录返回的token\n\t\"data\": []/{} //数据，数组/对象\n}\n</code></pre>\n<h4>socket数据接收格式</h4>\n<pre class=\"prettyprint\">{\n\t\"action\": \"xxx\",  //字符串\n\t\"code\": 0, // 0=成功 -1=失败 4001=未登录或登录超时\n\t\"msg\": \"xxx\", //信息\n\t\"order_version\":x, //int 当前订单版本号，用于对比本地版本号控制拉单\n\t\"data\": []/{} //数据，数组/对象\n}\n</code></pre>\n<h2>api版本更新说明</h2>\n<h3>3.6.0版本</h3>\n<h4>app接口</h4>\n<ul>\n<li>提交订单接口改成 <code>?ct=order&amp;ac=post_order</code> 增加 <code>pay_type</code> 必填参数</li>\n</ul>\n<h4>H5接口</h4>\n<ul>\n<li>增加 <code>?ct=coupon&amp;ac=get_activity_11_coupon</code> 领取双十一优惠券接口</li>\n<li>增加 <code>?ct=coupon&amp;ac=check_activity_11_coupon</code> 是否领取双十一优惠券接口</li>\n</ul>\n"
  },
  "template": {
    "withCompare": true,
    "withGenerator": true,
    "aloneDisplay": false
  },
  "order": [],
  "defaultVersion": "0.0.0",
  "apidoc": "0.3.0",
  "generator": {
    "name": "apidoc",
    "time": "2021-10-01T06:16:35.021Z",
    "url": "https://apidocjs.com",
    "version": "0.29.0"
  }
});
