<?php

//เรียกใช้งาน LineClass.php
require_once($_SERVER['DOCUMENT_ROOT']."/LineClass.php");

//สร้าง Instance LINE Class ด้วยคำสั่ง
$LINE=new LINE();

//============= เลือกใช้ อันใดอันหนึ่ง ระหว่าง ============//
//1. กำหนด Access Token ให้กับ Class ด้วยคำสั่ง 
$LINE->setAccessToken("ใส่ Access Token ของตัวเองไว้ที่นี่");

//หรือ

//2. จะสร้าง Access Token ขึ้นมาใหม่ โดยใช้ Client ID, Client Secret ด้วยคำสั่ง
$clientID="ระบุ Client ID";
$clientSecret="ระบุ Client Secret";
$accessToken=$LINE->getAccessToken($clientID,$clientSecret);

//**(Access Token, ClientID, ClientSecret สามารถดูได้ที่ LINE Developer Console)

//==================================================//


//เมื่อกำหนดทุกอย่างเสร็จแล้ว เราสามารถ
//1. ส่งข้อความด้วย Push Messages ด้วยคำสั่ง
$response = $LINE->pushMessages($userId,[
    "สวัสดี โลกใบนี้",
    "Hello World"
]);

//2. ตอบกลับ Messages ด้วย Reply Message ด้วยคำสั่ง
$response = $LINE->replyMessages($replyToken,[
    "สวัสดี โลกใบนี้",
    "Hello World"
]);

//3. ตอบกลับด้วย Reply Message และ Quick Reply
$quickReply1 = array(
    "type" => "action",
    "action" => array(
        "type" => "camera",
        "label" => "ถ่ายรูป"
    )
);
$quickReply2 = array(
    "type" => "action",
    "action" => array(
        "type" => "cameraRoll",
        "label" => "เลือกรูป"
    )
);
$quickReplyData=array($quickReply1,$quickReply2);
$response = $LINE->replyMessageswithQuickReply($replyToken,"กรุณาส่งรูปมาให้ระบบ",$quickReplyData);


//4. จัดการ Rich Menu
//============== จัดการ Rich Menu ==================//
// ขั้นตอนที่ 1 เราจะต้องสร้าง RichMenuId ของเราออกมาก่อน โดยเราจะใช้ไฟล์ rich_menu_template เป็นตัวตั้ง
$richMenuId=$LINE->createDefaultRichMenu("rich_menu_template.json"); //ใส่เป็น URL ของ json ที่เรา Config Rich Menu ไว้เรียบร้อยแล้ว
$richMenuId=json_decode($richMenuId,true);
$richMenuId=$richMenuId['richMenuId'];

// ขั้นตอนที่ 2 เราจะต้องอัปโหลดรูปภาพที่เราเตรียมไว้ ใส่ rich menu Id ที่เราเพิ่งสร้างเมื่อกี้ ***ภาพจะต้องอยู่บน Server ของเราเอง ไม่สามารถใช้ Remote URL ได้
$LINE->uploadImageToRichMenu($richMenuId,$_SERVER['DOCUMENT_ROOT'].'/test.jpg');


// ขั้นตอนที่ 3 เราจะ Push Rich Menu ที่เราสร้างไปให้ User (โดยใช้ UserId);
$LINE->pushRichMenuToUser($richMenuId,$userId);

//กรณีที่เราต้องการ ลบ Rich Menu จาก User และกลับไปใช้แบบ Default ใช้คำสั่ง
$LINE->deleteRichMenuFromUser($userId);

// Developer Note: 
// การทำงานคือ เราสร้าง Rich Menu ที่ต้องการไว้ก่อน จากนั้นเมื่อ User มีการ Interact กับ Bot ของเรา เราก็ค่อยยิงคำสั่ง pushRichMenuToUser ไปให้
// ซึ่งเราสามารถสร้าง Rich Menu เตรียมไว้หลายๆ อันได้, สร้างแล้ว จด Rich Menu Id เอาไว้ อยากให้เปลี่ยนเป็นอันไหน ก็ยิงอันนั้นไปครับ
//===================================================//


//5. ส่งและตอบกลับรูปแบบ Flex Messages
$flexData="flex.json";  //เราสามารถใช้ json ที่ได้จาก LINE Flex Simulator ได้เลย สร้างได้ที่ => https://developers.line.biz/flex-simulator/
$response=$LINE->replyFlex($replyToken,$flexData,$title);
$response=$LINE->pushFlex($userId,$flexData,$title);
// ในกรณีที่เราต้องการ Edit Flex ก่อนส่ง เราสามารถ แปลง Flex เป็น Array แล้ว Edit เนื้อหาข้างในก่อน แล้วค่อยส่งก็ได้ครับ 
// เป็นวิธีที่เราสามารถทำ Flex เป็น Variable ได้นั่นเอง แต่อย่าลืม แปลงกลับเป็น json ด้วยนะครับ
// json_decode("flex.json",true); เพื่อแปลงเปน Array ก่อนแก้ไข
// json_encode("flex.json",JSON_UNESCAPED_UNICODE); เพื่อแปลงกลับก่อนส่งครับ

//6. ส่งภาพให้ User 
$response=$LINE->pushImage($userId,$url,$tn);  //$url คือภาพไซส์จริง ส่วน $tn คือภาพ Thumbnail ที่ย่อขนาดแล้วครับ
$response=$LINE->replyImage($replyToken,$url,$tn);  //$url คือภาพไซส์จริง ส่วน $tn คือภาพ Thumbnail ที่ย่อขนาดแล้วครับ

//7. ส่ง Image Map ให้ User อย่างง่าย (เป็นปุ่มใหญ่ปุ่มเดียวขนาด 1040 x 1040)
$imageURL="https://image_url";
$response=$LINE->replyImageMap(
    $replyToken,
    $imageURL."?_ignore=",
    "Text หัวข้อที่จะให้ขึ้น",
    "Link ที่ต้องการ"
);
$response=$LINE->pushImageMap(
    $userId,
    $imageURL."?_ignore=",
    "Text หัวข้อที่จะให้ขึ้น",
    "Link ที่ต้องการ"
);

//8. PushCarousel  รออัพเดทนะคับ ....
?>