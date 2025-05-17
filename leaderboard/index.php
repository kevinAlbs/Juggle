<html>

<head>
    <title>Juggle</title>
    <meta name="viewport" content="width=462, maximum-scale=1, user-scalable=no">
    <link rel="icon" type="image/png" href="../favicon.png">
    <style type="text/css">
        * {
            color: #FFF;
            font-family: monospace;
            margin: 0px;
            padding: 0px;
        }

        body {
            background: #000;
        }


        body h1 {
            color: #eee;
            margin-bottom: 1em;
        }

        th {
            text-align: left;
        }

        body.dark a {
            color: #FFF;
        }

        body.dark .popup a {
            color: initial;
        }
        h1 {
            font-size: 20px;
        }

        #main {
            margin: 10px;
        }

        #main table {
            margin-bottom: 1.2em;
            table-layout: fixed;
            position: relative;
            left: 70px;
            font-size: 1.2em;
        }

        .entry {
            position: relative;
        }

        #main table tr td:first-child {
            width: 4em;
        }

        .entry .place {
            position: absolute;
            left: 0px;
            top: 15px;
            border-radius: 10px;
            display: inline-block;
            color: #111;
            font-weight: bold;
            background: #FFF;
            padding: 3px;
            font-size: 2em;
        }
    </style>
</head>

<body>
    <div id="main">

        <h1>Juggle Leaderboard - Top 100</h1>
        <?php

        // Create/open the database
        $db = new SQLite3('juggle.db');

        // Create a table
        $query = "CREATE TABLE IF NOT EXISTS scores (
            name TEXT,
            score INTEGER,
            scoreid TEXT,
            date INTEGER
        )";
        $db->exec($query);

        $stmt = $db->prepare("SELECT * FROM scores ORDER BY score DESC LIMIT 100");
        $res = $stmt->execute();
        $place = 1;
        function leftpad($place) {
            while (strlen($place) < 3) {
                $place = "0" . $place;
            }
            return $place;
        }
        while ($row = $res->fetchArray()) {
            echo "<div class='entry'>";
            echo "<div class='place'>" . leftpad($place) . "</div>";
            echo "<table>";
            printf("<tr><td>Name...</td><td>%s</td></tr>", htmlspecialchars($row["name"]));
            printf("<tr><td>Score..</td><td>%s</td></tr>", htmlspecialchars($row["score"]));
            printf("<tr><td>Date...</td><td class='date' data-dateunix='%d'></td></tr>", htmlspecialchars($row["date"]));
            echo "</table>";
            echo "</div>";
            $place++;
        }

        if ($place == 1) {
            echo "Nothing yet. Submit a score!";
        }

        // Close the database connection
        $db->close();
        ?>
    </div>

    <script>
        if (window.localStorage.getItem("dark_mode") == "on") {
            document.body.classList.add("dark");
        }
        document.querySelectorAll(".date").forEach((date_el) => {
            const date_unix = parseInt(date_el.getAttribute("data-dateunix"));
            const date_date = new Date(date_unix);
            date_el.innerHTML = date_date.toLocaleString();
        });
    </script>
</body>

</html>