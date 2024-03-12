<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>政府工作报告双语语料库</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="" type="" />
    <style type="text/css">
        <!--
        body,
        td,
        th {
            font-family: Times New Roman, Times, serif;
            font-size: medium;
        }

        body {
            background-color: #CCCC66;
        }

        .STYLE1 {
            font-size: medium
        }

        .STYLE2 {
            font-size: large
        }
        -->
    </style>
</head>

<body>
    <div>
        <h1 align="center"><a href="index.html" style="text-decoration:none; color:white;">《政府工作报告》中英双语语料库</a></h1>
    </div>
    <form id="searchForm" action="result.php" method="post" class="form-wrapper cf" style="margin-top:20px; text-align: center">
        <input name="searchterm" type="text" class="STYLE2" placeholder="请输入要检索的关键词" />
        <select name="resultsPerPage">
            <option value="20" selected>每页20条</option>
            <option value="50">每页50条</option>
            <option value="100">每页100条</option>
        </select>
        <!-- Add a hidden input field for current page -->
        <input type="hidden" name="page" value="<?php echo $current_page; ?>">
        <button type="submit"><span class="STYLE2">搜索</span></button>
    </form>

    <?php
    $tablenames = array("gr2008", "gr2009", "gr2010", "gr2011", "gr2012", "gr2013", "gr2014", "gr2015", "gr2016", "gr2017", "gr2018", "gr2019", "gr2020", "gr2021", "gr2022");
    $tablecount = count($tablenames);
    $conn = mysqli_connect('localhost', 'root', '');
    mysqli_select_db($conn, 'gr_co');
    mysqli_query($conn, "set names utf8");
    $searchterm = isset($_REQUEST["searchterm"]) ? $_REQUEST["searchterm"] : '';//注意这里的获取 $searchterm 的方式
    $resultsPerPage = isset($_POST['resultsPerPage']) ? (int)$_POST['resultsPerPage'] : 20;
    $rowscount = 0;
    $rowsresult = array();
    for ($x = 0; $x < $tablecount; $x++) {
        $query = "SELECT * FROM " . $tablenames[$x] . " WHERE zh_CN LIKE '%$searchterm%' or en_US LIKE '%$searchterm%'";
        $result = mysqli_query($conn, $query);
        $rows = mysqli_num_rows($result);
        $rowscount += $rows;
        while ($line = mysqli_fetch_array($result)) {
            $line["table"] = substr($tablenames[$x], 2);
            array_push($rowsresult, $line);
        }
    }

    printf("<table class=\"table table-bordered\" align=\"center\">
              <tr><td><h2>搜索\"%s\"的结果：共计%d条</h2></td></tr></table>", htmlspecialchars($searchterm), $rowscount);//htmlspecialchars转义方法

    echo '<table border="1" align="center" width="80%"><tr><th>年份</th><th>中文</th><th>英文</th></tr>';

    $current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $startIndex = ($current_page - 1) * $resultsPerPage;
    $endIndex = min($startIndex + $resultsPerPage, $rowscount);

    for ($x = $startIndex; $x < $endIndex; $x++) {
        echo "<tr>";
        echo "<td><font size=5>" . $rowsresult[$x]["table"] . "</font></td>";
        $highlighted_zh_CN = preg_replace('/(' . preg_quote($searchterm, '/') . ')/i', '<span style="background-color: yellow;">$1</span>', $rowsresult[$x]["zh_CN"]);
        echo "<td><font size=5>" . $highlighted_zh_CN . "</font></td>";
        $highlighted_en_US = preg_replace('/(' . preg_quote($searchterm, '/') . ')/i', '<span style="background-color: yellow;">$1</span>', $rowsresult[$x]["en_US"]);
        echo "<td><font size=5>" . $highlighted_en_US . "</font></td>";
        echo "</tr>";
    }
    echo "</table>";

    echo '<div style="text-align: center; margin-top: 10px;">';
    for ($i = 1; $i <= ceil($rowscount / $resultsPerPage); $i++) {
        $isActive = ($i == $current_page) ? 'active' : '';
        echo '<a href="?page=' . $i . '&searchterm=' . urlencode($searchterm) . '" class="' . $isActive . '">' . $i . '</a> ';
    }
    echo '</div>';
    ?>

    <script>
        function updatePage(page) {
            document.querySelector('input[name="page"]').value = page;
            document.forms["searchForm"].submit();
        }
    </script>

    <?php
    mysqli_free_result($result);
    mysqli_close($conn);
    ?>

</body>

</html>
