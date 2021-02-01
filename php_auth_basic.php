<?php
if ($_SERVER['REQUEST_URI'] == "/playlist/5e-klass-gimnaziya-9-im-n-ostrovskogo/603/") {
  $login = "user5e";
  $password = "sschool5e";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo ''; 
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      echo 'Authenticate required!';
      header('HTTP/1.0 401 Unauthorized');
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/5d-klass-gimnaziya-9-im-n-ostrovskogo/600/") {
  $login = "user5d";
  $password = "bschool5d";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/profile/gimnaziya-9-im-n-ostrovskogo/546/") {
  $login = "user1";
  $password = "qwe123qwe";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

// mn 
if ($_SERVER['REQUEST_URI'] == "/playlist/5g-klass-gimnaziya-9-im-n-ostrovskogo/599/") {
  $login = "user5g";
  $password = "aschool5g";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}


if ($_SERVER['REQUEST_URI'] == "/playlist/5a-klass-gimnaziya-9-im-n-ostrovskogo/601/") {
  $login = "user5a";
  $password = "sschool5a";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

////////////////////////////////////////////////////
/////////////////////////////////////////////////////

if ($_SERVER['REQUEST_URI'] == "/playlist/5b-klass-gimnaziya-9-im-n-ostrovskogo/598/") {
  $login = "user5b";
  $password = "zschool5b";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}
/////////////////////////////////////////////////////
if ($_SERVER['REQUEST_URI'] == "/playlist/5v-klass-gimnaziya-9-im-n-ostrovskogo/602/") {
  $login = "user5v";
  $password = "rschool5v";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/6a-klass-gimnaziya-9-im-n-ostrovskogo/635/") {
  $login = "user6a";
  $password = "rscehool6a";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}
if ($_SERVER['REQUEST_URI'] == "/playlist/6g-klass-gimnaziya-9-im-n-ostrovskogo/636/") {
  $login = "user6g";
  $password = "eschool6g";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}
if ($_SERVER['REQUEST_URI'] == "/playlist/6d-klass-gimnaziya-9-im-n-ostrovskogo/638/") {
  $login = "user6d";
  $password = "wschool6d";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}
if ($_SERVER['REQUEST_URI'] == "/playlist/6e-klass-gimnaziya-9-im-n-ostrovskogo/640/") {
  $login = "user6e";
  $password = "school6e";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/6v-klass-gimnaziya-9-im-n-ostrovskogo/639/") {
  $login = "user6v";                  
  $password = "wschool6v";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/6b-klass-gimnaziya-9-im-n-ostrovskogo/637/") {
  $login = "user6b";
  $password = "fschool6b";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/7e-klass-gimnaziya-9-im-n-ostrovskogo/684/") {
  $login = "user7e";
  $password = "wschool7e";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}
if ($_SERVER['REQUEST_URI'] == "/playlist/7a-klass-gimnaziya-9-im-n-ostrovskogo/679/") {
  $login = "user7a";
  $password = "vschool7a";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}
if ($_SERVER['REQUEST_URI'] == "/playlist/7v-klass-gimnaziya-9-im-n-ostrovskogo/681/") {
  $login = "user7v";
  $password = "vschool7v";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}
if ($_SERVER['REQUEST_URI'] == "/playlist/7b-klass-gimnaziya-9-im-n-ostrovskogo/680/") {
  $login = "user7b";
  $password = "vschool7b";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}
if ($_SERVER['REQUEST_URI'] == "/playlist/7g-klass-gimnaziya-9-im-n-ostrovskogo/682/") {
  $login = "user7g";
  $password = "vschool7g";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/7d-klass-gimnaziya-9-im-n-ostrovskogo/683/") {
  $login = "user7d";
  $password = "wschool7d";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}
if ($_SERVER['REQUEST_URI'] == "/playlist/8a-klass-gimnaziya-9-im-n-ostrovskogo/685/") {
  $login = "user8a";
  $password = "eschool8a";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}
if ($_SERVER['REQUEST_URI'] == "/playlist/8b-klass-gimnaziya-9-im-n-ostrovskogo/686/") {
  $login = "user8b";
  $password = "vschool8b";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}
if ($_SERVER['REQUEST_URI'] == "/playlist/8v-klass-gimnaziya-9-im-n-ostrovskogo/687/") {
  $login = "user8v";
  $password = "bschool8v";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}
if ($_SERVER['REQUEST_URI'] == "/playlist/8g-klass-gimnaziya-9-im-n-ostrovskogo/688/") {
  $login = "user8g";
  $password = "rschool8g";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/8d-klass-gimnaziya-9-im-n-ostrovskogo/689/") {
  $login = "user8d";
  $password = "wschool8d";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/8e-klass-gimnaziya-9-im-n-ostrovskogo/690/") {
  $login = "user8e";
  $password = "vschool8e";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/9b-klass-gimnaziya-9-im-n-ostrovskogo/692/") {
  $login = "user9b";
  $password = "2school9b";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/9a-klass-gimnaziya-9-im-n-ostrovskogo/691/") {
  $login = "user9a";
  $password = "fschool9a";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/9v-klass-gimnaziya-9-im-n-ostrovskogo/693/") {
  $login = "user9v";
  $password = "qschool9v";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/9g-klass-gimnaziya-9-im-n-ostrovskogo/694/") {
  $login = "user9g";
  $password = "vschool9g";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/9d-klass-gimnaziya-9-im-n-ostrovskogo/695/") {
  $login = "user9d";
  $password = "dschool9d";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/9e-klass-gimnaziya-9-im-n-ostrovskogo/696/") {
  $login = "user9e";
  $password = "dschool9e";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/10a-klass-gimnaziya-9-im-n-ostrovskogo/697/") {
  $login = "user10a";
  $password = "dschool10a";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/10b-klass-gimnaziya-9-im-n-ostrovskogo/698/") {
  $login = "user10b";
  $password = "sschool10b";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/10v-klass-gimnaziya-9-im-n-ostrovskogo/699/") {
  $login = "user10v";
  $password = "aschool10v";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/10d-klass-gimnaziya-9-im-n-ostrovskogo/700/") {
  $login = "user10d";
  $password = "xschool10d";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/10e-klass-gimnaziya-9-im-n-ostrovskogo/701/") {
  $login = "user10e";
  $password = "zschool10e";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}


if ($_SERVER['REQUEST_URI'] == "/playlist/10g-klass-gimnaziya-9-im-n-ostrovskogo/702/") {
  $login = "user10g";
  $password = "vschool10g";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/11a-klass-gimnaziya-9-im-n-ostrovskogo/703/") {
  $login = "user11a";
  $password = "aschool11a";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}
if ($_SERVER['REQUEST_URI'] == "/playlist/11b-klass-gimnaziya-9-im-n-ostrovskogo/704/") {
  $login = "user11b";
  $password = "cschool11b";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/11v-klass-gimnaziya-9-im-n-ostrovskogo/705/") {
  $login = "user11v";
  $password = "xschool11v";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/11g-klass-gimnaziya-9-im-n-ostrovskogo/706/") {
  $login = "user11g";
  $password = "xschool11g";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/11d-klass-gimnaziya-9-im-n-ostrovskog/707/") {
  $login = "user11d";
  $password = "cschool11d";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

if ($_SERVER['REQUEST_URI'] == "/playlist/11e-klass-gimnaziya-9-im-n-ostrovskog/708/") {
  $login = "user11e";
  $password = "zschool11e";

  if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
    // авторизован успешно
  echo '';
  } else {
    // если ошибка при авторизации, выводим соответствующие заголовки и сообщение
      header('WWW-Authenticate: Basic realm="Backend"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authenticate required!';
  }
}

