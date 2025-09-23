<?php 

return[
    'server_key' => env('MIDTRANS_SERVER_KEY', 'Mid-server-key'),
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'Mid-key'),
    // true untuk production,false untuk sandbox
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    // jika true maka akan nampilin notifikasi log/response
    'is_sanbox' => true,
    // jika true maka akan nampilin Log curl saat proses
    'is_3ds' => true,
];

?>