<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/component/config.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/component/link_db.php");


class LINE
{
    public function getAccessToken($client_id, $client_secret)
    {
        $url = 'https://api.line.me/v2/oauth/accessToken';
        $grant_type = "client_credentials";
        $post = "grant_type=$grant_type&client_id=$client_id&client_secret=$client_secret";
        $headers = array('Content-Type: application/x-www-form-urlencoded');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        $access_token = json_decode($result);
        $access_token = $access_token->access_token;
        $this->access_token = $access_token;
        return $access_token;
    }
    public function setAccessToken($token)
    {
        $this->access_token = $token;
    }
    public function pushMessages($userId, $messagesArray)
    {
        $access_token = $this->access_token;
        $sendArray = array();
        for ($i = 0; $i < count($messagesArray); $i++) {
            array_push($sendArray, array("type" => 'text', 'text' => $messagesArray[$i]));
        }
        // Make a POST Request to Messaging API to reply to sender
        $url = 'https://api.line.me/v2/bot/message/push';
        $data = array('to' => $userId, 'messages' => $sendArray);
        $post = json_encode($data);
        $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    public function replyMessages($replyToken, $messagesArray)
    {
        $access_token = $this->access_token;
        $sendArray = array();
        for ($i = 0; $i < count($messagesArray); $i++) {
            array_push($sendArray, array("type" => 'text', 'text' => $messagesArray[$i]));
        }
        // Make a POST Request to Messaging API to reply to sender
        $url = 'https://api.line.me/v2/bot/message/reply';
        $data = array('replyToken' => $replyToken, 'messages' => $sendArray);
        $post = json_encode($data);
        $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    public function pushImage($userId,$url,$tnUrl)
    {
        $access_token = $this->access_token;
        $messages = array('type' => 'image', 'originalContentUrl' => $url, 'previewImageUrl' => $tnUrl);
        // Make a POST Request to Messaging API to reply to sender
        $url = 'https://api.line.me/v2/bot/message/push';
        $data = array('to' => $userId, 'messages' => array($messages));

        $post = json_encode($data);
        $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    public function replyImage($replyToken,$url,$tnUrl)
    {
        $access_token = $this->access_token;
        $messages = array('type' => 'image', 'originalContentUrl' => $url, 'previewImageUrl' => $tnUrl);
        // Make a POST Request to Messaging API to reply to sender
        $url = 'https://api.line.me/v2/bot/message/reply';
        $data = array('replyToken' => $replyToken, 'messages' => array($messages));

        $post = json_encode($data);
        $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    public function GetProfile($userId)
    {
        $access_token = $this->access_token;
        $url = "https://api.line.me/v2/bot/profile/" . $userId;
        $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    public function replyImageMap($replyToken, $imageUrl, $altText, $redirectUri)
    {
        $access_token = $this->access_token;
        $sendArray = array(
            "type" => 'imagemap',
            "baseUrl" => $imageUrl,
            "altText" => $altText,
            "baseSize" => array("width" => 1040, "height" => 1040),
            "actions" => array(array("type" => "uri", "linkUri" => $redirectUri, "area" => array("x" => 0, "y" => 0, "width" => 1040, "height" => 1040)))
        );
        // Make a POST Request to Messaging API to reply to sender
        $url = 'https://api.line.me/v2/bot/message/reply';
        $data = array('replyToken' => $replyToken, 'messages' => array($sendArray));
        $post = json_encode($data);
        $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    public function pushImageMap($userId, $imageUrl, $altText, $redirectUri)
    {
        $access_token = $this->access_token;
        $sendArray = array(
            "type" => 'imagemap',
            "baseUrl" => $imageUrl,
            "altText" => $altText,
            "baseSize" => array("width" => 1040, "height" => 1040),
            "actions" => array(array("type" => "uri", "linkUri" => $redirectUri, "area" => array("x" => 0, "y" => 0, "width" => 1040, "height" => 1040)))
        );
        // Make a POST Request to Messaging API to reply to sender
        $url = 'https://api.line.me/v2/bot/message/push';
        $data = array('to' => $userId, 'messages' => array($sendArray));
        $post = json_encode($data);
        $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    public function replyFlex($replyToken, $jsonURL, $title = "ส่ง Flex Messages")
    {
        $access_token = $this->access_token;
        $flexContent = file_get_contents($jsonURL);
        $flexContent = json_decode($flexContent, true);
        $sendArray = array(
            array("type" => "text", "text" => $title),
            array("type" => 'flex', "altText" => $title, "contents" => $flexContent),
        );
        // Make a POST Request to Messaging API to reply to sender
        $url = 'https://api.line.me/v2/bot/message/reply';
        $data = array('replyToken' => $replyToken, 'messages' => $sendArray);
        $post = json_encode($data);
        $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    public function pushFlex($userId, $jsonURL, $title = "ส่ง Flex Messages")
    {
        $access_token = $this->access_token;
        $flexContent = file_get_contents($jsonURL);
        $flexContent = json_decode($flexContent, true);
        $sendArray = array(
            array("type" => "text", "text" => $title),
            array("type" => 'flex', "altText" => $title, "contents" => $flexContent),
        );
        // Make a POST Request to Messaging API to reply to sender
        $url = 'https://api.line.me/v2/bot/message/push';
        $data = array('to' => $userId, 'messages' => $sendArray);
        $post = json_encode($data);
        $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    public function replyMessageswithQuickReply($replyToken, $text, $quickReplyArray)
    {
        $access_token = $this->access_token;
        $sendArray = array(
            array(
                "type" => 'text',
                'text' => $text,
                "quickReply" => array("items" => $quickReplyArray)
            )
        );
        // Make a POST Request to Messaging API to reply to sender
        $url = 'https://api.line.me/v2/bot/message/reply';
        $data = array('replyToken' => $replyToken, 'messages' => $sendArray);
        $post = json_encode($data);
        $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function createDefaultRichMenu($templateName)
    {
        $access_token = $this->access_token;
        $url = 'https://api.line.me/v2/bot/richmenu';
        $post = file_get_contents($templateName);
        $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    public function uploadImageToRichMenu($richMenuId, $imagePath)
    {
        $access_token = $this->access_token;
        $url = 'https://api-data.line.me/v2/bot/richmenu/' . $richMenuId . '/content';
        $imagePath = file_get_contents($imagePath);
        $post = array('file' => $imagePath, 'type' => 'image/jpeg');
        $headers = array('Content-Type: image/jpeg', 'Authorization: Bearer ' . $access_token);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $imagePath);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    public function pushRichMenuToUser($richMenuId, $userId)
    {
        $access_token = $this->access_token;
        $url = 'https://api.line.me/v2/bot/user/' . $userId . '/richmenu/' . $richMenuId;
        $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
        $ch = curl_init($url);
        $post = "";
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    public function deleteRichMenuFromUser($userId)
    {
        $access_token = $this->access_token;
        $url = 'https://api.line.me/v2/bot/user/' . $userId . '/richmenu';
        $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
        $ch = curl_init($url);
        $post = "";
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
