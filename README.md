# cloud
Zaliczenie przedmiotu programowanie aplikacji internetowych w chmurze obliczeniowej
###
Instrukcja uruchomienia:
1. Pobieramy wszystkie pliki z repozytorium  np. poprzez git clone <repozytorium>
2. W folderze z plikeim Dockerfile wpisujemy komendę docker build .
3. Komendą docker images sprawdzamy image id
4. Kontener uruchamiamy poprzez docker docker run -d -h localhost -p 80:80 <image ID>
5. Usługę najlepiej "wystawić w świat" ponieważ skrypt może nie poprawnie wykrywać time zone oraz czas na jego podstawie ze względu na lokalny adres IP
6. Usuługę możemy przetestować poprzez wejście w przeglądarkę poprzez localhost/index.php

#Sprawdzanie logów
  Logi możemy sprawdzić poprzez:
  1. docker ps, aby sprawdzic container ID
  2. docker exec -it <container id/name> /bin/sh
  3. cat MYLOG.log
  
#Sprawdzenie ilości warst oraz ich wagi
  1. Ilość warst możemy sprawdzić poprzez docker history <container id>
  
