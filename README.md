Zadanie:

Klient chce w swoim serwisie zaimplementować możliwość składania aplikacji do pracy. W tym celu musisz przygotować endpointy do zapisu i odczytu złożonych podań w Symfony.
Encja będzie składała się z pól potrzebnych do rekrutacji, czyli imię, nazwisko, email, nr telefonu, oczekiwane wynagrodzenie, stanowisko, poziom oraz te pola, które uznasz za konieczne lub przydatne.
Aplikacja musi wystawiać 4 endpointy API (w tym celu możesz wspomóc się API Platform), jeden do zapisu nowego podania, jeden dla pobrania podania o danym ID oraz dwa dla pobrania kolekcji wszystkich podań - po jednym dla podań nowych i dla podań już wyświetlonych.
Endpointy do pobierania kolekcji powinny umożliwiać wybranie pola i kierunku do sortowania. Możliwości filtrowania i paginacji są opcjonalne.
Pola powinny być walidowane przed zapisem.
Poziom w encji powinien być zapisywany automatycznie na podstawie minimalnego oczekiwanego wynagrodzenia: poniżej 5 000 - junior; 5 000 - 9 999 - regular; ponad 10 000 - senior.
