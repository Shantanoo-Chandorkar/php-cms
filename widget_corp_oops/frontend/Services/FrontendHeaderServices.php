<?php 
namespace Widget_Corps_Oops_Frontend\Services;

class FrontendHeaderServices
{
    public function getHeader(string $stylesheetName): string
    {
        $baseUrl = $this->getSiteURL();

        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Widget Corp Content</title>
            <link href="<?php echo $baseUrl; ?>stylesheets/<?php echo htmlspecialchars($stylesheetName); ?>.css" 
                  rel="stylesheet" type="text/css">
        </head>
        <body>
        <div id="header" class="header">
            <h1>Widget Corp</h1>
        </div>
        <div id="main" class="main">
        <?php
        return ob_get_clean();
    }

    private function getSiteURL(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
        $scriptPath = rtrim(str_replace('/admin', '', $scriptPath), '/\\');
        return $protocol . "://" . $host . $scriptPath . '/';
    }
}
