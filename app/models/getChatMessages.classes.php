<?php
class GetChatMessages extends Dbh {

	protected function getChatMessages($senderId, $receiverId, $username, $type) {

		// fetch all messages using user ids of either the sender or receiver
		if($type == 0) {
			$stmt = $this->connect()->prepare('SELECT * FROM messages WHERE (sender_id=? AND receiver_id=?) OR (sender_id=? AND receiver_id=?);'); // connect to database
		} else {
			$stmt = $this->connect()->prepare('SELECT * FROM messages WHERE groupid = ?;'); // connect to database
		}
		
//if groupchat instead of receiver id use groupid
		// if prepared statement can't connect to the database
		if($type == 0) {
			if(!$stmt->execute(array($senderId, $receiverId, $receiverId, $senderId))) 
			{
				$stmt = null;
				$alert['message'] = 'Error! The server is having trouble connecting to the database';
				$alert['type'] = 'error';
				echo json_encode($alert);
				exit();
			}
		} else {
			if(!$stmt->execute(array($receiverId))) 
			{
				$stmt = null;
				$alert['message'] = 'Error! The server is having trouble connecting to the database';
				$alert['type'] = 'error';
				echo json_encode($alert);
				exit();
			}
		}
		
		// store message results in $user
		$user = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// for all returned messages in $user
		for($i=0;$i<count($user);$i++) {

			// get privatekey of $username/session
			$privateKey = $this->getPrivateKey($username);

			//get private pwd of $username/session
			$privateKeyPwd = $this->getPrivateKeyPwd($username);

			// get data of the sender of the message
			$senderInfo = $this->getSenderInfo($user[$i]['sender_id']);
		
			// private key packet
			$keyEncrypted = OpenPGP_Message::parse(OpenPGP::unarmor($privateKey, "PGP PRIVATE KEY BLOCK"));

			// for each private key packet decrypt message
			foreach($keyEncrypted as $p) {
				if(!($p instanceof OpenPGP_SecretKeyPacket)) continue;
				
				$key = OpenPGP_Crypt_Symmetric::decryptSecretKey($privateKeyPwd, $p);
				$msg = OpenPGP_Message::parse(OpenPGP::unarmor($user[$i]['message'], 'PGP MESSAGE'));

				$decryptor = new OpenPGP_Crypt_RSA($key);
				$decrypted = $decryptor->decrypt($msg)->packets[0]->data;

			}

			// store all message data in $data object
			$data['messages'][$user[$i]['id']]['sender_id'] = $user[$i]['sender_id'];
			$data['messages'][$user[$i]['id']]['receiver_id'] = $user[$i]['receiver_id'];
			$data['messages'][$user[$i]['id']]['message'] = $decrypted;
			$data['messages'][$user[$i]['id']]['sessionUid'] = $_SESSION['UID'];
			$data['messages'][$user[$i]['id']]['sessionUser'] = $_SESSION['USERNAME'];
			$data['messages'][$user[$i]['id']]['messageUid'] = $senderInfo[1];
			$data['messages'][$user[$i]['id']]['messageUser'] = $senderInfo[0];
			$data['messages'][$user[$i]['id']]['date'] = $user[$i]['timestamp'];
			
			if($senderInfo[3] == 1) {
			    $data['messages'][$user[$i]['id']]['messagePic'] = $senderInfo[1].'_'.$senderInfo[0].'.jpg';
			} else {
			    $data['messages'][$user[$i]['id']]['messagePic'] = 'no_pic.jpg';
			}
			$_SESSION['sender_info'] = $data;

			
		}

		// return message $data object
		echo json_encode($data);
	
	}
		


	// fetch user's private key from database
	private function getPrivateKey($uid) {

		// prepare statement
		$stmt = $this->connect()->prepare('SELECT privatekey FROM privatekeys WHERE uid = ?;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($uid))) 
		{
			$stmt = null;
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// fetch user's public key
		$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		// return users public key
		return $user[0]['privatekey'];

	}

	// fetch user's private key password from database
	private function getPrivateKeyPwd($uid) {

		// prepare statement
		$stmt = $this->connect()->prepare('SELECT pwd FROM privatepwd WHERE uid = ?;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($uid))) 
		{
			$stmt = null;
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// fetch user's public key
		$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		// return users public key
		return $user[0]['pwd'];

	}

	// fetch user's private key password from database
	private function getSenderInfo($uid) {

		// prepare statement
		$stmt = $this->connect()->prepare('SELECT * FROM users WHERE id = ?;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($uid))) 
		{
			$stmt = null;
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// fetch user's public key
		$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		// create array
		$senderInfo = [];

		// push into senderInfo
		array_push($senderInfo, $user[0]['username'], $user[0]['id'], $user[0]['friends'], $user[0]['profile_pic']);

		// return senderInfo
		return $senderInfo;

	}
	
}