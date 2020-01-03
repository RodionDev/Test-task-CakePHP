<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <?= $this->Html->css('home.css') ?>
    <title>Document</title>
</head>
<body>
    <div class="wrapper">
        <form method="post" action="/privacy">
            <div class="form-group">
                <label for="plaintiffs">Plaintiffs</label>
                <input name="plaintiffs" required id="plaintiffs" type="text" class="form-control">
                <small class="form-text text-muted">Enter plaintiffs. Use a semicolon (";") as a separator</small>
            </div>
            <div class="form-group">
                <label for="defendants">Defendants</label>
                <input name="defendants" required id="defendants" type="text" class="form-control">
                <small class="form-text text-muted">Enter defendants. Use a semicolon (";") as a separator</small>
            </div>
            <div class="form-group form-check">
                <input name="DNCR" type="checkbox" class="form-check-input" id="DNCR">
                <label class="form-check-label" for="DNCR">DNCR Violation</label>
            </div>
            <div class="form-group form-check">
                <input name="IDNCL" type="checkbox" class="form-check-input" id="IDNCL">
                <label class="form-check-label" for="IDNCL">IDNCL Violation</label>
            </div>
            <div class="form-group form-check">
                <input name="TIAA" type="checkbox" class="form-check-input" id="TIAA">
                <label class="form-check-label" for="TIAA">TIAA Violation</label>
            </div>
            <button type="submit" class="btn btn-primary">Generate policy</button>
        </form>

        <?php
        if (isset($policy)) {
            ?>
            <div class="card">
                <div class="card-body">
                    <?=$policy?>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</body>
</html>