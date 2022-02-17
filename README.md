# iepay-sdk
Iepay PHP SDK version 3.x APIs.

## Installation / 安装
```bash
composer require iepay/iepay-sdk
```

## Usage / 使用方法
### Add environment config / 添加环境变量
```code
MYPAY_ID={YOUR_IEPAY_ID}		#IEPAY账号下的MID
MYPAY_KEY={YOUR_IEPAY_API_KEY}	#IEPAY账号下的API-KEY

MYPAY_RETURN_URL={PAYMENT_SUCCESS_PAGE}			#交易成功页面地址
MYPAY_NOTIFY_URL={PAYMENT_SUCCESS_NOTIFY_URL}	#交易成功异步通知地址
```

### Add namespace / 添加文件命名空间
```code
use IEPaySDK\Payment;
```


### Payment / 支付
```code
$paymentType = 'IE0012';			// Payment Type see types table (支付类别，参考支付类别表)
$orderId = '20220210001';			// Your order id (随机生成的订单号)
$amount = 100;						// Cent (分)
$subject = 'test subject';			// Item subject (商品标题)
$details = 'test details';			// Item details (商品详情)
$response = Payment::make($paymentType)->payment($orderId, $amount, $subject, $details);
```

#### Customise return URL or notify URL / 自定义返回链接
```code
$returnUrl = 'https://xxx.xxx.xxx/return';
$notifyUrl = 'https://xxx.xxx.xxx/notify';
$response = Payment::make($paymentType, $returnUrl, $notifyUrl)->payment($orderId, $amount, $subject, $details);
```

#### Response / 返回值
```json
{
    "success": true,
    "status": true,
    "error_code": 0,
    "message": "OK",
    "data": {
        "pay_type": "IE0012",
        "pay_type_str": "Alipay Web",
        "out_trade_no": "20220210001",
        "trade_no": null,
        "amount": 102,
        "status": 0,
        "status_str": "Unpay",
        "pay_url": "https://intlmapi.alipay.com/gateway.do?xxxxxxxx=xxxxxxxx&xxxxxxxx=xxxxxxxx"
    }
}
```
```json
{
    "success": true,
    "status": true,
    "error_code": 0,
    "message": "OK",
    "data": {
        "pay_type": "IE0026",
        "pay_type_str": "Wechat Mini",
        "out_trade_no": "20220210001",
        "trade_no": null,
        "amount": 102,
        "status": 0,
        "status_str": "Unpay",
        "pay_param": {
            "appId": "wx123123",
            "timeStamp": 1644978320,
            "nonceStr": "cpxg8dl123123mi4oysnfx",
            "package": "prepay_id=wx123456",
            "signType": "MD5",
            "paySign": "96DB44xxxxxxxxxxx"
        },
        "pay_url": null
    }
}
```

### Query / 查询
```code
$paymentType = 'IE0012';			// Payment Type see types table (支付类别，参考支付类别表)
$orderId = '20220210001';			// Your order id (需要查询的订单号)
$response = Payment::make($paymentType)->query($orderId);
```
#### Response / 返回值
```json
{
    "success": true,
    "status": true,
    "error_code": 0,
    "message": "OK",
    "data": {
        "pay_type": "IE0061",
        "pay_type_str": "Windcave Host Page Payment",
        "out_trade_no": "20220210005",
        "trade_no": "000001011336275001f9e4140da76641",
        "amount": 101,
        "status": 1,
        "status_str": "Paid",
        "refunded_amount": 0,
        "pay_url": "https://sec.windcave.com/pxmi3/F7F7CF323F53968E56CA0650BE713B347D965FA2CC2827399D145DE371986332500051BD83B6BCC7B8D21D53BAB554800"
    }
}
```
```json
{
    "success": true,
    "status": true,
    "error_code": 0,
    "message": "OK",
    "data": {
        "pay_type": "IE0026",
        "pay_type_str": "Wechat Mini",
        "out_trade_no": "20220210001",
        "trade_no": null,
        "amount": 102,
        "status": 0,
        "status_str": "Unpay",
        "pay_param": {
            "appId": "wx123123",
            "timeStamp": 1644978320,
            "nonceStr": "cpxg8dl123123mi4oysnfx",
            "package": "prepay_id=wx123456",
            "signType": "MD5",
            "paySign": "96DB44xxxxxxxxxxx"
        },
        "pay_url": null
    }
}
```

### Refund / 退款
```code
$paymentType = 'IE0012';			// Payment Type see types table (支付类别，参考支付类别表)
$orderId = '20220210001';			// Your order id (需要退款的订单号)
$amount = 100;						// Cent (分)
$response = Payment::make($paymentType)->refund($orderId, $amount);
```
#### Response / 返回值
```json
{
    "success": true,
    "status": true,
    "error_code": 0,
    "message": "OK",
    "data": {
        "pay_type": "IE0061",
        "pay_type_str": "Windcave Host Page Payment",
        "out_trade_no": "20220210005",
        "trade_no": "000001011336275001f9e4140da76641",
        "amount": 101,
        "status": 2,
        "status_str": "Refund",
        "refunded": 10
    }
}
```


## Pay Type Table / 支付类别表
|**Code**|**Pay Types**|**Desc**|**Status**|
|:-----|:-----------------------|:--------------------------------------|:-------|
|IE0012|Alipay Online PC Qrcode|支付宝PC二维码(PC收银页面)|已开通|
|IE0013|Alipay Online Wap Qrcode|支付宝H5手机端二维码(Wap 收银页面)|已开通|
|IE0015|Alipay App Direct|支付宝In-APP支付(native开发，需接入支付宝APP-SDK)|已开通|
|IE0021|Wechat PC Qrcode|微信PC二维码|已开通|
|IE0022|Wechat Wap Qrcode|微信WAP支付|已开通|
|IE0025|Wechat App Direct|微信In-APP支付|已开通|
|IE0026|Wechat Mini Direct|微信小程序支付|已开通|
|IE0036|POLI Pay|POLI PC 支付|已开通|
|IE0041|Union Secure Online Payment|银联Secure线上支付|新开通|
|IE0061|Windcave Host Page Payment|Windcave PC 支付|即将开通|