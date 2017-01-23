<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= h($this->fetch('title')) ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>
    <?= $this->Html->css('/adminLTE/dist/css/AdminLTE.min.css')?>
    <?= $this->Html->css('/adminLTE/bootstrap/css/bootstrap.min.css')?>
    <?= $this->Html->css('/adminLTE/plugins/iCheck/square/blue.css')?>
    <?= $this->Html->script('/adminLTE/plugins/jQuery/jquery-2.2.3.min.js')?>
    <?= $this->Html->script('/adminLTE/bootstrap/js/bootstrap.min.js')?>
    <?= $this->Html->script('/adminLTE/plugins/iCheck/icheck.min.js')?>
    <?= $this->fetch('css')?>
    <?= $this->fetch('script')?>
</head>
<body class="hold-transition login-page">
    <?= $this->Flash->render() ?>
    <?= $this->Flash->render('auth') ?>
    <?= $this->fetch('content');?>
    <script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>