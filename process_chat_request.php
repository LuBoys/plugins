<?php
// Clé d'API OpenAI
$openai_token = "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";

// Définir le prompt pour ChatGPT
$chat_prompt = "À partir de la description de la page fournie par l'utilisateur, veuillez générer une meta description de 160 caractères maximum adaptée au référencement, ainsi que 6 mots-clés pertinents.";

// Récupérer le message de l'utilisateur depuis le formulaire
$user_message = $_POST['user_message'];

// Préparer les données pour la requête à OpenAI
$data = array(
    "model" => "gpt-3.5-turbo",
    "messages" => array(
        array(
            "role" => "user",
            "content" => $user_message
        ),
        array(
            "role" => "system",
            "content" => $chat_prompt
        )
    ),
    "max_tokens" => 250
);

// Préparer les en-têtes de la requête
$headers = array(
    "Content-Type: application/json",
    "Authorization: Bearer " . $openai_token
);

// Initialiser une session cURL
$ch = curl_init();

// Configurer les options de la requête cURL
curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Exécuter la requête cURL et récupérer la réponse
$response = curl_exec($ch);

// Fermer la session cURL
curl_close($ch);

// Retourner la réponse
echo $response;
?>
