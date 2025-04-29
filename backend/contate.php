<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nome = htmlspecialchars($_POST['nome']);
  $email = htmlspecialchars($_POST['email']);
  $telefone = htmlspecialchars($_POST['telefone']);
  $mensagem = htmlspecialchars($_POST['mensagem']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

  <title>Hope</title>

  <!-- slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css" />

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="../frontend/css/bootstrap.css" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css?family=Lato:400,700|Poppins:400,700|Roboto:400,700&display=swap" rel="stylesheet" />

  <!-- Custom styles for this template -->
  <link href="../frontend/css/style.css" rel="stylesheet" />
  <link href="../frontend/css/responsive.css" rel="stylesheet" />
</head>

<body class="sub_page">
  <div class="hero_area">
    <!-- header section -->
    <header class="header_section">
      <div class="container">
        <nav class="navbar navbar-expand-lg custom_nav-container ">
          <a class="navbar-brand" href="../index.html">
            <img src="../frontend/css/images/logo.png" alt="Hope" />
            <span>Hope</span>
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="d-flex ml-auto flex-column flex-lg-row align-items-center">
              <ul class="navbar-nav">
        
                
              </ul>
            </div>
          </div>
        </nav>
      </div>
    </header>
    <!-- end header section -->
  </div>

  <!-- mensagem de agradecimento -->
  <section class="contact_section layout_padding">
    <div class="container">
      <div class="heading_container">
        <h2>Mensagem <span>Enviada</span></h2>
      </div>
      <div class="row">
        <div class="col-md-8 mx-auto">
          <div class="alert alert-success p-4" role="alert" style="font-size: 1.2rem;">
            <strong>Obrigado, <?php echo $nome; ?>!</strong><br>
            Sua mensagem foi enviada com sucesso.<br>
            Entraremos em contato pelo e-mail <strong><?php echo $email; ?></strong> ou telefone <strong><?php echo $telefone; ?></strong>.
          </div>
          <div class="text-center mt-4">
        
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- info section -->
  <section class="info_section layout_padding">
    <div class="container">
      <div class="info_form">
        <div class="row">
          <div class="offset-lg-3 col-lg-3">
          </div>
          <div class="col-md-6">
            <form action="#">
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
            </form>
          </div>
        </div>
      </div>
      <div class="row">
        <!-- rodapé omitido, igual ao original -->
      </div>
    </div>
  </section>

  <!-- footer section -->
  <section class="container-fluid footer_section">
  </section>

  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>

</body>
</html>
<?php
} else {
  // acesso direto sem POST
  header("Location: contact.html");
  exit;
}
?>
