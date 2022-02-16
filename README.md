# iepay-sdk
Iepay PHP SDK version 3.x.

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


### Refund / 退款
```code
$paymentType = 'IE0012';			// Payment Type see types table (支付类别，参考支付类别表)
$orderId = '20220210001';			// Your order id (需要退款的订单号)
$amount = 100;						// Cent (分)
$response = Payment::make($paymentType)->refund($orderId, $amount);
```
#### Response / 返回值
