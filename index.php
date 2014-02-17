<? include('manifest.php') ?>
<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Netflix Delivery Manifest Generator</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <div class="container">
            <div class="jumbotron">
                <h1>Netflix Delivery Manifest Generator</h1>
            </div>

            <form class="form-horizontal" method="post" role="form">
                <div class="form-group">
                    <label for="inputContentProvider" class="col-sm-2 control-label">Content Provider <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputContentProvider" name="inputContentProvider" placeholder="Content Provider" required>
                        <p class="help-block">Rights holder of content. Provided by Netflix, contact your Digital Account Specialist.</p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="optionsContentType" class="col-sm-2 control-label">Content Type <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <div class="radio">
                            <label>
                                <input type="radio" name="optionsContentType" value="TV_EPISODE" checked>Television Episode
                            </label>
                        </div>
                    </div>
                </div>

                <div id="tv-episode" class="">
                    <div class="form-group">
                        <label for="inputShowName" class="col-sm-2 control-label">Series Name <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputShowName" name="inputShowName" placeholder="Series Name" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputOriginalTitle" class="col-sm-2 control-label">Episode Title <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputOriginalTitle" name="inputOriginalTitle" placeholder="Episode Title" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputFirstReleaseYear" class="col-sm-2 control-label">Release Year <span class="text-danger">*</span></label>
                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="inputFirstReleaseYear" name="inputFirstReleaseYear" value="<?= date('Y') ?>" placeholder="Release Year" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="optionsContentLanguage" class="col-sm-2 control-label">Content Language <span class="text-danger">*</span></label>
                        <div class="col-sm-3">
                            <select class="form-control" id="optionsContentLanguage" name="optionsContentLanguage">
                                <option value="en">English</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputVideoFileName" class="col-sm-2 control-label">Video File Name <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputVideoFileName" name="inputVideoFileName" placeholder="Video File Name" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="optionsAudioChannels" class="col-sm-2 control-label">Audio Channels <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select class="form-control" id="optionsAudioChannels" name="optionsAudioChannels">
                                <option value="2-0">Stereo</option>
                                <option value="5-1">5.1</option>
                                <option value="7.1">7.1</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Generate Manifest</button>
                    </div>
                </div>
            </form>

            <footer class="footer">
                <p>Copyright &copy; <?= date('Y') ?> Group 6 Technologies</p>
            </footer>
        </div>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="scripts/main.js"></script>
    </body>
</html>