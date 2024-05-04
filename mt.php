<?php
// ----------------------
// 作者：在意 zai1.com
// 来源：https://zai1.com/word/141.html
// 本仓库仅用于分享，之前不知道作者，现在才知道，特意加上作者的版权出处
// 作者要求随意转载，但需要保留出处
// 本仓库地址：https://github.com/zixiwu/mt-img-bed
// ----------------------
// 检查是否有文件上传
if(isset($_FILES['file'])) {
    // 获取文件信息
    $file = $_FILES['file'];
    // 设置请求头
    $headers = array(
        'Accept: */*',
        'Accept-Encoding: gzip, deflate, br',
        'Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6',
        'Cache-Control: no-cache',
        'Connection: keep-alive',
        'Content-Type: multipart/form-data; boundary=----WebKitFormBoundarywt1pMxJgab51elEB',
        'Host: pic-up.meituan.com',
        'Origin: https://czz.meituan.com',
        'Pragma: no-cache',
        'Referer: https://czz.meituan.com/',
        'Sec-Fetch-Dest: empty',
        'Sec-Fetch-Mode: cors',
        'Sec-Fetch-Site: same-site',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36 Edg/121.0.0.0',
        'client-id: p5gfsvmw6qnwc45n000000000025bbf1',
        'sec-ch-ua: "Not A(Brand";v="99", "Microsoft Edge";v="121", "Chromium";v="121"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "Windows"',
        'token: 这里填美团开放平台的token，在这里登陆获取即可czz.meituan.com'
    );
    // 构建 multipart/form-data 格式的数据
    $postData = "------WebKitFormBoundarywt1pMxJgab51elEB\r\n";
    $postData .= 'Content-Disposition: form-data; name="file"; filename="' . $file['name'] . "\"\r\n";
    $postData .= 'Content-Type: ' . $file['type'] . "\r\n\r\n";
    $postData .= file_get_contents($file['tmp_name']) . "\r\n";
    $postData .= "------WebKitFormBoundarywt1pMxJgab51elEB--\r\n";
    // 初始化 cURL
    $ch = curl_init();
    // 设置 cURL 选项
    curl_setopt($ch, CURLOPT_URL, 'https://pic-up.meituan.com/extrastorage/new/video?isHttps=true');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // 执行请求并获取响应
    $response = curl_exec($ch);
    // 检查是否有错误发生
    if(curl_errno($ch)){
        echo 'Curl error: ' . curl_error($ch);
        exit;
    }
    // 关闭 cURL 资源
    curl_close($ch);
    // 解析 JSON 响应
    $jsonResponse = json_decode($response, true);
    // 检查是否上传成功
    if(isset($jsonResponse['success']) && $jsonResponse['success'] === true) {
        // 提取原始链接和原始文件名
        $originalLink = $jsonResponse['data']['originalLink'];
        $originalFileName = $jsonResponse['data']['originalFileName'];
        
        // 组成新的 JSON 并输出
        $newJson = array(
            'Jobs' => $originalLink,
            'Name' => $originalFileName,
            'os'=>'node-oss.zai1.com'
        );
        echo json_encode($newJson);
    } else {
        // 输出上传失败信息
        echo json_encode(array('error' => 'Upload failed'));
    }
} else {
    echo "No file uploaded.";
}
?>
