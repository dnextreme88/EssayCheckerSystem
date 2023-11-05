<?php
    include 'db.php';

    session_start();
    // error_reporting(0);

    $user = $_SESSION['user'];
    $selectedClass = $_SESSION['selectedclass'];
    if (!isset($_SESSION['user'])) {
        echo "<script>window.location.href = 'index.php';</script>";
    }

    $sqlstore = "SELECT * FROM user WHERE username = '$user'";
    $resultstore = $conn->query($sqlstore);
    $ln = '';
    $fn = '';
    $pass = '';
    $userimage = '';
    while ($rowstore = $resultstore->fetch_assoc()) {
        $userimage = $rowstore['image'];
        $ln = $rowstore['lastname'];
        $fn = $rowstore['firstname'];
        $pass = $rowstore['password'];
    }

    $teachercount = 0;
    $studentessay = '';
    $studentcount = 0;
    $matchwords = 0;
    $ave = 0.0;

    function removeCommonWords($input) {
        $stopwords = array(
            "a", "about", "above", "above", "across", "after",
            "afterwards", "again", "against", "all", "almost", "alone", "along",
            "already", "also", "although", "always", "am", "among", "amongst", "amoungst",
            "amount",  "an", "and", "another", "any", "anyhow", "anyone", "anything",
            "anyway", "anywhere", "are", "around", "as",  "at", "back", "be", "became",
            "because", "become", "becomes", "becoming", "been", "before", "beforehand",
            "behind", "being", "below", "beside", "besides", "between", "beyond", "bill",
            "both", "bottom", "but", "by", "call", "can", "cannot", "cant", "co", "con",
            "could", "couldnt", "cry", "de", "describe", "detail", "do", "done", "down",
            "due", "during", "each", "eg", "eight", "either", "eleven", "else", "elsewhere",
            "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything",
            "everywhere", "except", "few", "fifteen", "fify", "fill", "find", "fire", "first",
            "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", "full",
            "further", "get", "give", "go", "had", "has", "hasnt", "have", "he", "hence", "her", "here",
            "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him", "himself", "his", "how",
            "however", "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "it", "its",
            "itself", "keep", "last", "latter", "latterly", "least", "less", "ltd", "made", "many", "may", "me",
            "meanwhile", "might", "mill", "mine", "more", "moreover", "most", "mostly", "move", "much", "must",
            "my", "myself", "name", "namely", "neither", "never", "nevertheless", "next", "nine", "no", "nobody",
            "none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on", "once", "one",
            "only", "onto", "or", "other", "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own",
            "part", "per", "perhaps", "please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming",
            "seems", "serious", "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so",
            "some", "somehow", "someone", "something", "sometime", "sometimes", "somewhere", "still", "such", "system",
            "take", "ten", "than", "that", "the", "their", "them", "themselves", "then", "thence", "there", "thereafter",
            "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this", "those",
            "though", "three", "through", "throughout", "thru", "thus", "to", "together", "too", "top", "toward", "towards",
            "twelve", "twenty", "two", "un", "under", "until", "up", "upon", "us", "very", "via", "was", "we", "well", "were",
            "what", "whatever", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein",
            "whereupon", "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose",
            "why", "will", "with", "within", "without", "would", "yet", "you", "your", "yours", "yourself", "yourselves", "the"
        );

        return preg_replace('/\b(' .implode('|', $stopwords). ')\b/', '', $input);
    }

    /*
    function strip_stopwords($str = '') {
        $stopwords;
        $words = preg_split('/[^-\w\']+/', $str, -1, PREG_SPLIT_NO_EMPTY);
        if (count($words) > 1) {
            $words = array_filter($words, function($w) use(&$stopwords) {
                return !isset($stopwords[strtolower($w)]);
            });
        }

        if (!empty($words)) {
            return implode(' ', $words);
        }

        return $str;
    }
    */

    $essayid = $_SESSION['essayid'];
    $sqlstore = "SELECT answer FROM essay_answer WHERE essay_id = '$essayid' AND username = '$user'";
    $resultstore = $conn->query($sqlstore);
    while ($rowstore = $resultstore->fetch_assoc()) {
        $studentessay =   $rowstore['answer'];
    }

    $str = '';
    $mwsPercentage = 0;
    $gsPercentage = 0;
    $sqlstore = "SELECT essay_content,mws,gs FROM essay WHERE essay_id = '$essayid'";
    $resultstore = $conn->query($sqlstore);
    while ($rowstore = $resultstore->fetch_assoc()) {
        $str =   removeCommonWords($rowstore['essay_content']);
        $mwsPercentage = $rowstore['mws'];
        $gsPercentage =  $rowstore['gs'];
    }

    $string = $str;
    $wordsTemp  = explode(' ',  $string);
    $counterKWT = 0;
    for ($x = 0; $x < count($wordsTemp); $x++) {
        if ($wordsTemp[$x] != '' and $wordsTemp[$x] != ' ') {
            $counterKWT++;
        }
    }

    $words = new SplFixedArray($counterKWT);
    $ctrAddedTeacher = 0;
    for ($x = 0; $x < count($wordsTemp); $x++) {
        if ($wordsTemp[$x] != '' and $wordsTemp[$x] != ' ') {
            $words[$ctrAddedTeacher] = $wordsTemp[$x];
            // echo "<script>alert('Teacher" .$words[$ctrAddedTeacher]. "')</script>";
            $ctrAddedTeacher++;
        }
    }

    $str2 = $studentessay;
    $string2 = removeCommonWords($str2);
    $wordsTemp2  = explode(' ', $string2);
    $counterKWS = 0;
    for ($x = 0; $x < count($wordsTemp2); $x++) {
        if ($wordsTemp2[$x] != '' and $wordsTemp2[$x] != ' ') {
            $counterKWS++;
        }
    }

    $words2 = new SplFixedArray($counterKWS);
    $ctrAddedStudent = 0;
    for ($x = 0; $x < count($wordsTemp2); $x++) {
        if ($wordsTemp2[$x] != '' and $wordsTemp2[$x] != ' ') {
            $words2[$ctrAddedStudent] = $wordsTemp2[$x];
            // echo "<script>alert('Student" .$words2[$ctrAddedStudent]. "')</script>";
            $ctrAddedStudent++;
        }
    }

    $matchwords = 0;
    $totalmatchWords = 0;
    $matchwordDisplay = 0;
    for ($x = 0; $x < count($words); $x++) {
        $kw_score = 1;
        $sqlstore = "SELECT * FROM essay_keyword WHERE essay_kw = '$words[$x]' AND essay_id = '$essayid'";
        $resultstore = $conn->query($sqlstore);
        while ($rowstore = $resultstore->fetch_assoc()) {
            $kw_score = $rowstore['kw_score'];
            break;
        }

        for ($y = 0; $y < count($words2); $y++) {
            if ($words[$x] == $words2[$y]) {
                if ($kw_score > 0) {
                    $matchwords += $kw_score;
                    $totalmatchWords += $kw_score;
                } else {
                    $matchwords++;
                    $totalmatchWords++;
                }
                $matchwordDisplay++;
                $words[$x] = '';
                $words2[$y] = '.';

                break;
            } else {
                $API_KEY = '7d528bee8151ed04676514d489eac650';
                $srch = $words[$x];
                $request = 'http://words.bighugelabs.com/api/2/' .$API_KEY. '/' .$srch. '/json';
                $response = file_get_contents($request);
                $jsonobj = json_decode($response);
                if ($jsonobj->noun->syn) {
                    foreach ($jsonobj->noun->syn as $character) {
                        // echo $character. '<br />';
                        if (strcasecmp($character, $words2[$y])) {
                            if ($kw_score > 0) {
                                $matchwords += $kw_score;
                                $totalmatchWords += $kw_score;
                            } else {
                                $matchwords++;
                                $totalmatchWords++;
                            }

                            $matchwordDisplay++;
                            $words[$x] = '';
                            $words2[$y] = '.';

                            break;
                        }
                    }
                } else if ($jsonobj->adjective->syn) {
                    foreach ($jsonobj->adjective->syn as $character) {
                        // echo $character. '<br />';
                        if (strcasecmp($character, $words2[$y])) {
                            if ($kw_score > 0) {
                                $matchwords += $kw_score;
                                $totalmatchWords += $kw_score;
                            } else {
                                $matchwords++;
                                $totalmatchWords++;
                            }

                            $matchwordDisplay++;
                            $words[$x] = '';
                            $words2[$y] = '.';

                            break;
                        }
                    }
                } else if ($jsonobj->adjective->sim) {
                    foreach ($jsonobj->adjective->sim as $character) {
                        // echo $character. '<br />';
                        if (strcasecmp($character, $words2[$y])) {
                            if ($kw_score > 0) {
                                $matchwords += $kw_score;
                                $totalmatchWords += $kw_score;
                            } else {
                                $matchwords++;
                                $totalmatchWords++;
                            }

                            $matchwordDisplay++;
                            $words[$x] = '';
                            $words2[$y] = '.';

                            break;
                        }
                    }
                } else {
                    $totalmatchWords++;

                    break;
                }
            }
        }
    }

    $teachercount = count($words); // BILANG NG WORDS NI TEACHER SA ESSAY
    $studentcount = count($words2); // BILANG NG WORDS NI STUDENT SA ESSAY

    // mwsPercentage = Ung diniclare ni teacher na percentage
    // $matchwords = ung score ng nag match na words ni teacher at student..
    // $totalmatchWords = ung score ng nag match na words ni teacher at student AT ung lahat ng hindi nag match
    if ($teachercount == $matchwordDisplay and $teachercount == $studentcount) {
        $ave = 100 *  ($mwsPercentage / 100);
    } else {
        $ave = round(($matchwords / $totalmatchWords) * 100) * ($mwsPercentage / 100);
    }

    if (isset($_POST['checkresult'])) {
        $score = $_POST['total'];
        $sql = "UPDATE essay_answer SET score = '$score' WHERE username = '$user' AND essay_id = '$essayid'";
        $result = $conn->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: " .mysqli_error(), E_USER_ERROR);
    }

    include 'studentNavi.php';
?>
<html>

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <?php include 'headercontent.php'; ?>
</head>

<body>
    <div id="container">
        <div id="header">
            <label style="top: 5px; left: 15px; font-size: 30px; position: absolute;">Vanguard Essay Checker</label>
        </div>

        <fieldset id="teachprofile">
            <img src="<?= 'data:image/jpeg;base64,' .base64_encode($userimage) ?>" alt="No image" class="profimage" id="blah" />
            <br /><br />
            <label style="font-size: 15px; font-family: Arial, Charcoal, sans-serif;">ID: <?= $user ?></label>
        </fieldset>

        <fieldset id="myClassroom" style="height: auto;">
            <iframe name="votar" style="display: none;"></iframe>

            <form method="POST" action="#" target="votar">
                <br /><br />
                <label><b>Your Essay Status: </b>Submitted (If you don't click the compute score button your score will be zero) </label>
                <br />

                <textarea cols="100" roWs="10" name="test2" id="test2" readonly><?= $studentessay ?></textarea>
                <br /><br />
                <input type="submit" name="checkresult" value="Compute Score" onclick="showDiv()" />
                <br />

                <div id="welcomeDiv" style="display: none;" class="answer_list">
                    <label><b>Match Word Summary: </b></label>
                    <br /><br />

                    <label style="color: red;">NOTE: Common Words/Stopwords are not counted in both Teacher and Student Word Count!</label>
                    <br />

                    <label>Teacher Word Count: <?= $teachercount ?></label>
                    <br />
                    <label>Student Word Count: <?= $studentcount ?></label>
                    <br />
                    <label>Match Words Count: <?= $matchwordDisplay ?></label>
                    <br />
                    <label>Match Words Score(<?= $mwsPercentage ?>%): </label><input type="number" name="ave" id="ave" readonly value="<?= $ave ?>" />
                    <br /><br />

                    <label><b>Grammar & Spelling Summary: </b></label>
                    <br /><br />

                    <label>Wrong Spelling & Bad Usage: </label>
                    <br />
                    
                    <textarea cols="50" rows="5" name="essaysummary" readonly id="essaysummary"></textarea>
                    <br /><br />

                    <label>Grammar Score (<?= $gsPercentage ?>%): </label><input type="text" max="100" min="0" name="essayscore" readonly id="essayscore" />
                    <br /><br />

                    <label>Total Score (GS + MWS): </label><input type="text" name="total" id="total" readonly />
                    <br />
                </div>
            </form>
            <script type="text/javascript">
                $(document).ready(function UpdateStatus() {
                    // Make an ajax call and get status value using the same 'id'
                    var var1 = document.getElementById('test2').value;
                    var var2 = 'Vm9vg1Amzk4KcDGU';
                    $.ajax({
                        type: 'GET', // Or POST
                        url: 'https://api.textgears.com/check.php',
                        data: { text: var1, key: var2 },
                        // Can send multipledata like { data1: var1, data2: var2, data3: var3 }
                        // Can use dataType:'text/html' or 'json' if response type expected
                        success: function(responsedata) {
                            var num = <?= $gsPercentage ?>;
                            var data = JSON.stringify(responsedata);
                            var json = JSON.parse(data);
                            var wrongspell = '';
                            for (i = 0; i < json.errors.length; i++) {
                                wrongspell += JSON.stringify(json.errors[i].bad) + ',';
                            }

                            var textbox = document.getElementById('essayscore');
                            textbox.value = parseInt(json['score']) * (num / 100);

                            var summary = document.getElementById('essaysummary');
                            summary.value = wrongspell;

                            var ave = document.getElementById('ave');

                            var total = document.getElementById('total');
                            total.value = parseInt(ave.value) + parseInt(textbox.value);
                        }
                    })
                });

                function showDiv() {
                    document.getElementById('welcomeDiv').style.display = 'block';
                }
            </script>
        </fieldset>

        <fieldset id="submission">
            <?php include 'classMember.php'; ?>
        </fieldset>
    </div>
</body>
</html>
