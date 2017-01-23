
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        个人信息
        <small>编辑</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard">个人信息</i></a></li>
        <li class="active">编辑</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="col-lg-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">个人信息</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="/users/profile" method="post">
              <div class="box-body">
                <div class="form-group">
                  <label for="username">用户名</label>
                  <input type="text" class="form-control" id="username" placeholder="输入用户名">
                </div>
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" id="email" placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="password">新的密码</label>
                    <input type="password" class="form-control" id="password" placeholder="输入新密码">
                </div>
                <div class="form-group">
                    <label for="repassword">密码确认</label>
                    <input type="password" class="form-control" id="repassword" placeholder="再次输入新密码">
                </div>
                <div class="form-group">
                  <label for="exampleInputFile">头像</label>
                  <input type="file" id="exampleInputFile">
                  <!--<p class="help-block">Example block-level help text here.</p>-->
                </div>
                <!--<div class="checkbox">
                  <label>
                    <input type="checkbox"> Check me out
                  </label>
                </div>-->
                <div class="form-group">
                    <label>
                    <input type="radio" name="gender" class="minimal" checked>
                    </label>
                    <label>
                    <input type="radio" name="gender" class="minimal">
                    </label>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
          <!-- /.box -->
        </div>
    </section>
 <?= $this->start('script')?>
<?= $this->Html->script('/adminLTE/plugins/iCheck/icheck.min.js')?>
  <?= $this->end('script')?>
  documenu