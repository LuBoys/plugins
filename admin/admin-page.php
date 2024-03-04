<?php
function lo_seo_add_admin_page() {
    add_menu_page('Lucas Ozdemir SEO', 'SEO Lucas Ozdemir', 'manage_options', 'lucas-ozdemir-seo', 'lo_seo_create_page', 'dashicons-admin-generic', 110);
}
add_action('admin_menu', 'lo_seo_add_admin_page');

function lo_seo_create_page() {
    ?>
    <style>
        /* CSS modifié */
        .lo-seo-admin-page .edit-title {
            display: inline-block;
            width: 20px;
            height: 20px;
            background-color: #0073aa;
            color: #fff;
            font-size: 14px;
            text-align: center;
            line-height: 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            margin-right: 10px;
        }
        /* Style pour la page-container */
        .page-container {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        /* Style pour les titres des pages */
        .page-title {
            font-size: 20px;
            margin-bottom: 10px;
        }
        /* Style pour les formulaires */
        .seo-form {
            margin-top: 10px;
        }
        /* Style pour les boutons */
        .open-chat-button,
        .send-chat-button {
            background-color: #0073aa;
            color: #fff;
            border: none;
            border-radius: 3px;
            padding: 8px 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .open-chat-button:hover,
        .send-chat-button:hover {
            background-color: #005580;
        }
        /* Style pour les réponses du chat */
        .chat-response {
            margin-top: 10px;
            padding: 10px;
            background-color: #f0f0f0;
            border-radius: 3px;
        }
    </style>
    <div class="wrap">
        <h1>SEO Lucas Ozdemir</h1>
        <?php
        $posts = get_posts(array(
            'post_type' => array('post', 'page'),
            'post_status' => 'publish',
            'numberposts' => -1
        ));

        foreach ($posts as $post) {
            $post_id = $post->ID;
            $title = get_the_title($post_id);
            $seo_description = get_post_meta($post_id, '_lo_seo_description', true);
            $seo_keywords = get_post_meta($post_id, '_lo_seo_keywords', true);
            ?>
            <div class="page-container">
                <h3 class="page-title">
                    <span class="edit-title" data-post-id="<?php echo $post_id; ?>">✏️</span>
                    <?php echo $title; ?>
                </h3>
                <form method="post" class="seo-form">
                    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                    <input type="text" name="lo_seo_title" value="<?php echo esc_attr($title); ?>" class="widefat">
                    <textarea name="lo_seo_description" placeholder="Il faut 160 caractères maximum pour un meilleur référencement" class="widefat" maxlength="160"><?php echo esc_textarea($seo_description); ?></textarea>
                    <input type="text" name="lo_seo_keywords" value="<?php echo esc_attr($seo_keywords); ?>" placeholder="Mots-clés SEO (utilisez des tirets ou des virgules pour séparer les mots-clés)" class="widefat">
                    <button type="button" class="open-chat-button">IA</button>
                    <div class="chat-container" style="display: none;">
                        <textarea name="chat_prompt" placeholder="Décrivez la page pour obtenir une méta-description et des mots-clés." class="widefat"></textarea>
                        <button type="button" class="send-chat-button">Envoyer</button>
                        <div class="chat-response"></div> <!-- Champ pour afficher la réponse -->
                    </div>
                    <button type="submit" name="save_changes_button">Enregistrer les modifications</button>
                </form>
            </div>
            <?php
        }
        ?>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editTitleButtons = document.querySelectorAll('.edit-title');
            editTitleButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const postId = this.getAttribute('data-post-id');
                    const form = document.querySelector(`form.seo-form input[name="post_id"][value="${postId}"]`).parentNode;
                    if (form.style.display === 'none') {
                        form.style.display = 'block';
                    } else {
                        form.style.display = 'none';
                    }
                });
            });

            const openChatButtons = document.querySelectorAll('.open-chat-button');
            const chatContainers = document.querySelectorAll('.chat-container');

            openChatButtons.forEach(function(button, index) {
                button.addEventListener('click', function() {
                    if (chatContainers[index].style.display === 'none') {
                        chatContainers[index].style.display = 'block';
                    } else {
                        chatContainers[index].style.display = 'none';
                    }
                });
            });

            const sendChatButtons = document.querySelectorAll('.send-chat-button');
            const chatResponses = document.querySelectorAll('.chat-response');

            sendChatButtons.forEach(function(button, index) {
                button.addEventListener('click', function() {
                    const chatPrompt = document.querySelectorAll('textarea[name="chat_prompt"]')[index].value;
                    const postId = document.querySelectorAll('input[name="post_id"]')[index].value;

                    const xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                const responseData = JSON.parse(xhr.responseText);
                                chatResponses[index].textContent = responseData.choices[0].message.content;
                            } else {
                                chatResponses[index].textContent = "Erreur lors de la requête.";
                            }
                        }
                    };
                    xhr.open('POST', 'admin-ajax.php?action=process_chat_request');
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.send(`chat_prompt=${encodeURIComponent(chatPrompt)}&post_id=${encodeURIComponent(postId)}`);
                });
            });

            const descriptionTextareas = document.querySelectorAll('textarea[name="lo_seo_description"]');
            const charCounts = document.querySelectorAll('.char-count');
            const progressBars = document.querySelectorAll('.progress');
            
            descriptionTextareas.forEach(function(textarea, index) {
                textarea.addEventListener('input', function() {
                    const descriptionLength = this.value.length;
                    charCounts[index].textContent = descriptionLength;

                    const progressWidth = (descriptionLength / 160) * 100;
                    progressBars[index].style.width = progressWidth + '%';

                    if (descriptionLength <= 160) {
                        progressBars[index].classList.remove('green');
                    } else {
                        progressBars[index].classList.add('green');
                    }
                });
            });
        });
    </script>
    <?php
}

add_action('wp_ajax_process_chat_request', 'process_chat_request');
function process_chat_request() {
    // Clé d'API OpenAI
    $openai_token = "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";

    // Définir le prompt pour ChatGPT
    $chat_prompt = "À partir de la description de la page fournie par l'utilisateur, veuillez générer une meta description de 160 caractères maximum adaptée au référencement, ainsi que 6 mots-clés pertinents.";

    // Récupérer le message de l'utilisateur depuis le formulaire
    $user_message = $_POST['chat_prompt'];

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

    // Assurez-vous d'arrêter le script après l'envoi de la réponse
    die();
}
?>
    