<?php
/**
 * Bazowy landing page w czystym pliku PHP z osadzonym HTML/CSS
 * Autor: [Tu wpisz swoje imię/nazwisko lub nazwę]
 */

// Możesz tu opcjonalnie dodać kod PHP do obsługi formularzy,
// wykonywania logiki serwera itp.
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Moja Strona - Landing Page</title>
    <style>
        /* Prosty reset stylów */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f9f9f9;
        }

        header {
            background: #007BFF;
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        main {
            max-width: 800px;
            margin: 2rem auto;
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
        }

        h1, h2 {
            margin-bottom: 1rem;
        }

        .hero {
            text-align: center;
            margin-bottom: 2rem;
        }

        .hero img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .hero h2 {
            margin-top: 1rem;
            font-size: 1.8rem;
        }

        .cta-button {
            display: inline-block;
            margin-top: 1.5rem;
            padding: 0.75rem 1.5rem;
            background: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .cta-button:hover {
            background: #218838;
        }

        footer {
            text-align: center;
            padding: 1.5rem;
            margin-top: 2rem;
            background: #f0f0f0;
        }

        footer p {
            color: #777;
        }
    </style>
</head>
<body>

<header>
    <h1>Moja Firma</h1>
</header>

<main>
    <section class="hero">
        <img src="https://via.placeholder.com/800x400" alt="Zdjęcie promocyjne">
        <h2>Witamy na naszej stronie!</h2>
        <p>
            Oferujemy najlepsze produkty i usługi, które spełnią Twoje oczekiwania. 
            Zapoznaj się z naszą ofertą i dołącz do tysięcy zadowolonych klientów.
        </p>
        <a href="#" class="cta-button">Skontaktuj się z nami</a>
    </section>

    <section>
        <h2>Dlaczego warto nas wybrać?</h2>
        <p>
            Nasza firma to wieloletnie doświadczenie i zespół specjalistów, którzy zadbają 
            o to, byś otrzymał produkt najwyższej jakości.
        </p>
        <ul>
            <li>Wysoka jakość usług</li>
            <li>Szybki czas realizacji</li>
            <li>Indywidualne podejście do klienta</li>
        </ul>
    </section>
</main>

<footer>
    <p>&copy; 2025 Moja Firma. Wszelkie prawa zastrzeżone.</p>
</footer>

</body>
</html>
