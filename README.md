# fraud-detector
This project use graph database to check if a person is trying to cheat when purchasing our a e-commerce website

#usage

please POST the request to index.php, the POST should contain
- session_id
- ip address
- email address
- phone

sample:

array{
    'session_id' => 634ashdasdv1623=234,
    'ip' => '192.168.23.4',
    'email' => 'sample@gmail.com',
    'phone' => '+847236478234'
}

# return
array{
    'weight' => integer
}
