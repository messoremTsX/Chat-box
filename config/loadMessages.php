<?php
include 'config/sqltchat.php';

$query = $bdd->query('SELECT * FROM messages ORDER BY id DESC');

$messages = [];
while ($message = $query->fetch()) {
    $messageData = [
        'pseudo' => $message['pseudo'],
        'message' => $message['message'],
        'time' => time_elapsed_string($message['date'])
    ];
    $messages[] = $messageData;
}

echo json_encode($messages);

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    
    $days = $diff->days;
    $weeks = floor($days / 7);
    $days -= $weeks * 7;

    $string = array(
        'y' => 'an',
        'm' => 'mois',
        'w' => 'semaine',
        'd' => 'jour',
        'h' => 'heure',
        'i' => 'minute',
        's' => 'seconde',
    );
    
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }
    
    if (!$full) {
        $string = array_slice($string, 0, 1);
    }
    
    if ($diff->i == 0 && $diff->s < 60) {
        return "à l'instant";
    }
    
    return $string ? implode(', ', $string) . '' : 'maintenant';
}
?>
