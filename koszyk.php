<?php
session_start();//rozpoczęcie sesji w celu przekazania zamówienia
$conn = new mysqli("localhost", "root", "", "db_beton");//łączenie z BD
if ($conn->connect_error) die("Błąd połączenia z bazą danych");

$koszyk = $_SESSION['koszyk'] ?? []; //jeżeli koszyk jest pusty, do zmiennej koszyk przypisuje pustą tablice

if (empty($koszyk)) {//obsługa pustego koszyka (wysyła alert i przekierowywuje spowrotem na stronę zamowienia.php)
    echo "<!DOCTYPE html>
    <html lang='pl'>
    <head>
        <meta charset='UTF-8'>
        <title>Twój koszyk</title>
        <script>
            alert('Twój koszyk jest pusty. Zostaniesz przekierowany na stronę wyboru betonu.');
            window.location.href = 'zamowienia.php';
        </script>
    </head>
    <body></body>
    </html>";
    exit;
}

$ids = implode(',', array_map('intval', array_keys($koszyk)));//zmiana typu danych pobranych za pomocą array_keys czyli kluczy z koszyka w tym przypadku id_betonu na int oraz łączenie ich w jeden string oddzielając je przecinakami
$sql = "SELECT * FROM beton WHERE id IN ($ids)";
$result = $conn->query($sql);

$betony = [];
while ($row = $result->fetch_assoc()) {//przeszukiwanie tablicy ascojacyjnej w celu uzupełnienia tablicy $beton
    $betony[$row['id']] = $row;
}
//obsługa formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imie = $_POST['imie']; //pobranie danych z formularza 
    $nazwisko = $_POST['nazwisko'];
    $miejscowosc = $_POST['miejscowosc'];
    $adres = $_POST['adres'];
    $telefon = $_POST['telefon'];
    $mail = $_POST['mail'];

    $total = 0;
    foreach ($koszyk as $id => $ilosc) {
        $cena = $betony[$id]['cena']; //pobieramy cene betonu o danym id
        $total += $cena * $ilosc;
    }

    $ilosci_map = [ //przygotowanie tablicy asocjacyjnej żeby każdy beton miał swoja wartosc
        'lekki' => 0,
        'ciezki' => 0,
        'zwykly' => 0,
        'wysokowytrzymalosciowy' => 0,
        'architektoniczny' => 0,
        'samozageszczalny' => 0,
        'wodoodporny' => 0,
        'drogowy' => 0,
        'posadzkowy' => 0,
        'komorkowy' => 0
    ];

    foreach ($koszyk as $id => $ilosc) { //petla foreach przechodzi przez tablice koszyk, gdzie klucz to id a wartość to ilośc betonu dla danego id
        $nazwa = strtolower($betony[$id]['nazwa']);//zmiana stringa z nazwą betonu na małe litery
         if (isset($ilosci_map[$nazwa])) { //sprawdzanie, czy w tablicy $ilosci_map istnieje klucz o nazwie zawartej w zmiennej $nazwa
            $ilosci_map[$nazwa] = floatval($ilosc); //przypisuje wartość typu float do danej nazwy
        }
        }
    unset($wartosc);

    $sqlqr = "INSERT INTO zamowienia (
        imie, nazwisko, miejscowosc, adres, telefon, mail, cena_calkowita,
        `ilość betonu - lekki`, `ilość betonu - ciezki`, `ilość betonu - zwykly`,
        `ilość betonu - wysokowytrzymalosciowy`, `ilość betonu - architektoniczny`,
        `ilość betonu - samozageszczalny`, `ilość betonu - wodoodporny`,
        `ilość betonu - drogowy`, `ilość betonu - posadzkowy`, `ilość betonu - komorkowy`
    ) VALUES (
        '$imie', '$nazwisko', '$miejscowosc', '$adres', '$telefon', '$mail', $total,
        {$ilosci_map['lekki']}, {$ilosci_map['ciezki']}, {$ilosci_map['zwykly']},
        {$ilosci_map['wysokowytrzymalosciowy']}, {$ilosci_map['architektoniczny']},
        {$ilosci_map['samozageszczalny']}, {$ilosci_map['wodoodporny']},
        {$ilosci_map['drogowy']}, {$ilosci_map['posadzkowy']}, {$ilosci_map['komorkowy']}
    )";//zapytanie zapisujące zamówienie w tabeli zamowienia

    if ($conn->query($sqlqr)) {//jeśli wszystko zostało poprawnie zapisane to koszyk jest usuwany i wyświetla powiadomienie że zamówienie zostao przyjęte i przekierowywuje cię spowrotem na stronę zamówienie
        unset($_SESSION['koszyk']);
        echo "<script>
            alert('Zamówienie przyjęte!');
            window.location.href = 'zamowienia.php';
        </script>";
        exit;
    } else {
        echo "<p>Błąd zapisu zamówienia: " . $conn->error . "</p>";//jeżeli jednak się coś nie zapisało pokazuje error
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style_koszyk.css">
    <title>Koszyk</title>
</head>
<body>
<header>
    <h1>Twój koszyk</h1>
</header>
<main>
<form method="post">
<table border="1" cellpadding="5" cellspacing="0">
<tr><th>Beton</th><th>Cena (zł/m³)</th><th>Ilość (m³)</th><th>Wartość (zł)</th></tr>
<?php foreach ($koszyk as $id => $ilosc): 
    $beton = $betony[$id];
    $wartosc = $beton['cena'] * $ilosc;
?>
<tr>
    <td><?= $beton['nazwa'] ?></td>
    <td><?= number_format($beton['cena'], 2) ?></td>
    <td><?= number_format($ilosc, 2) ?></td>
    <td><?= number_format($wartosc, 2) ?></td> <!--wyświetlane zawsze z dwoma miejscami po przecinku-->
</tr>
<?php endforeach; ?>
<tr>
    <td colspan="3"><b>Razem:</b></td>
    <td>
        <?= number_format(array_sum(array_map(function($id) use ($betony, $koszyk) {
            return $betony[$id]['cena'] * $koszyk[$id];
        }, array_keys($koszyk))), 2) ?> <!-- Wylicza i wyświetla łączną wartość zamówienia (suma cena * ilość dla każdego betonu w koszyku), a wynik formatuje do 2 miejsc po przecinku, gdzie każda funkcja po kolei-->
        <!--array_keys($koszyk) — pobiera listę ID betonów z koszyka -->
        <!--array_map(...) — dla każdego ID betonu wylicza wartość jednej pozycji: cena * ilość.-->
        <!--array_sum(...) — sumuje wszystkie wartości pozycji, daje łączną kwotę zamówienia.-->

    </td>
</tr>
</table>

<h3>Dane do zamówienia</h3>
<p>Imię: <input type="text" name="imie" required></p>
<p>Nazwisko: <input type="text" name="nazwisko" required></p>
<p>Miejscowość: <input type="text" name="miejscowosc" required></p>
<p>Adres: <input type="text" name="adres" required></p>
<p>Telefon: <input type="text" name="telefon" required></p>
<p>E-mail: <input type="email" name="mail" required></p>

<button type="submit">Złóż zamówienie</button>
</form>
<div class="przyciski">
    <a href="wyczysc_koszyk.php"><button type="button">Wyczyść koszyk</button></a>
    <a href="zamowienia.php"><button type="button">Wróć do wyboru betonu</button></a>
</div>
</main>
<footer>
    <p>Wiktor Wójcik, Jakub Król, Kacper Krzemiński</p>
</footer>
</body>
</html>
