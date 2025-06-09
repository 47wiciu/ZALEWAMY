    <?php
    session_start();
    $conn = new mysqli("localhost", "root", "", "db_beton");//tworzenie połączenia z bazą danych
    if ($conn->connect_error) die("Błąd połączenia z bazą danych");//w przypadku błędu podczas łączenia, skrypt nie wykonuje się i wyświetla podany komunikat

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ilosc']) && is_array($_POST['ilosc'])) {//sprawdza czy formularz był wysłany metodą POST i czy przysładno dane w polu ilość ktore jest tablicą betonów i ich ilośći
        foreach ($_POST['ilosc'] as $id => $ilosc) {
            $id = (int)$id;
            $ilosc = floatval($ilosc);//konwersja w tej linijce na float, w poprzedniej na int
            if ($ilosc > 0) {
                $_SESSION['koszyk'][$id] = $ilosc;//jeśli ilość jest większa od 0 zapisuje ją do koszyka
            } else {
                unset($_SESSION['koszyk'][$id]);//jeśli jest równa 0 usuwa beton z koszyka
            }
        }
        header("Location: koszyk.php");//po zapisaniu koszyka następuje przekierowanie użytkownika na stronę koszyk.php
        exit;
    }

    $result = $conn->query("SELECT * FROM beton");//wysyła zapytanie, aby pobrać wszystkie wierze z tabeli beton
    ?>

    <!DOCTYPE html>
    <html lang="pl">
    <head>
        <meta charset="UTF-8">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="style_zamowienia.css">
        <title>Wybierz beton</title>
    </head>
    <body>
    <nav><p><a href="koszyk.php">Przejdź do koszyka</a></p><p><a href="wyczysc_koszyk.php">Wyczyść koszyk</a>
    </p><p><a href="index.php">Powróć do strony głównej</a></p></nav>
    <h2>Wybierz beton</h2>

    <form method="post">
    <div class="beton-grid">
    <?php while ($beton = $result->fetch_assoc()): ?> <!--dla każdego betonu tworzy kartę betonu-->
        <div class="karta">
            <h3><?= $beton['nazwa'] ?></h3>
            <p><strong><?= number_format($beton['cena'], 2) ?> zł/m³</strong></p><!--Tutaj znajduje się co będzie w karcie czyli nazwa betonu oraz jego cena-->

            <p>Ilość (m³):</p> 
            <input 
                type="number" 
                step="0.1" 
                min="0" 
                name="ilosc[<?= $beton['id'] ?>]" 
                required 
                class="ilosc-input" 
                data-cena="<?= $beton['cena'] ?>" 
                value="0"><br><br><!--Część kodu która odpowiada za tworzenie pola do wprowadzania ilości zamówionego betonu-->

            <div class="cena-aktualna">0,00 zł</div><!--Tutaj element który odpowiada za pokazywanie obecnej ceny za jeden typ betonu-->
        </div>
    <?php endwhile; ?>
    </div>

    <br>
    <div id="koszyk-suma">Wartość całego koszyka: 0,00 zł</div><!--Pokazywanie wartości całego koszyka-->
    <br>
    <button type="submit">Dodaj wszystkie do koszyka</button>
    </form>

    <script>
    function aktualizujCeneIKoszyk() {//Tworzenie funkcji do aktualizacji ceny betonu i koszyka
        let suma = 0;

        document.querySelectorAll('.karta').forEach(karta => {//funkcja querySelectorAll służy do znalezienia każdego elementu o klasie .karta, a póżniej dla każdego elementu o tej klasie pobniera wartości i liczy wartość betonu
            const input = karta.querySelector('.ilosc-input');//funkcja querySelector służy do znalezienia pierwszego elementu w tym przypadku o klasie ilosc-input i pobiera go do zmiennej input
            const cena = parseFloat(input.dataset.cena);
            const cenaAktualna = karta.querySelector('.cena-aktualna');

            let ilosc = parseFloat(input.value);
            if (isNaN(ilosc) || ilosc < 0) ilosc = 0;//w przypadku wpisania tekstu lub wartości mniejszej od 0 zmienia wartość automatycznie na 0

            const wartosc = cena * ilosc;
            cenaAktualna.textContent = wartosc.toFixed(2).replace('.', ',') + ' zł';//wyświetla wartość betonu w divie z dwoma liczbami po przecinku i zamiast kropki zamienia automatycznie na przecinek
            suma += wartosc;//dodaje kazda wartosc do sumy, przez co otrzymujemy wartość koszyka
        });

        document.getElementById('koszyk-suma').textContent =
            'Wartość całego koszyka: ' + suma.toFixed(2).replace('.', ',') + ' zł';//wyświetla wartość betonu w divie tym razem znalezionym pod ID z dwoma liczbami po przecinku i zamiast kropki zamienia automatycznie na przecinek
    }

    document.querySelectorAll('.ilosc-input').forEach(input => {
        input.addEventListener('input', aktualizujCeneIKoszyk);//ten fragment odpowiada za automatyczna zmiane koszyka i ceny betonu
    });

    aktualizujCeneIKoszyk();//wywołanie funkcji
    </script>

    </body>
    </html>
