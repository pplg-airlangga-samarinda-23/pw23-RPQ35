
                        <!--  everything is here  -->
                        <!-- edit,add,show,delete -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        main {
            float: right;
            width: 70%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        section#kiri {
            background-color: aquamarine;
            height: 100%;
            position: fixed;
            float: left;
            width: 20%;
            display: flex;
            padding-top: 5%;
            align-items: center;
            flex-direction: column;
            gap: 5%;

            div.button {
                display: flex;
                flex-direction: column;
                gap: 1vb;
            }
        }

        table,
        th,
        tr,
        td {
            border: 1px solid black;
        }

        div.square {
            height: 14vb;
            width: 14vb;
            background-color: aqua;
            border-radius: 15px;
            border: 2px solid black;
        }

        th {
            input {
                size: 100%;
                font-weight: 40px
            }
        }

        .tombol_magic {
            display: none;
        }
    </style>
</head>

<body>

    <section id=kiri>
        <div class="square"></div>
        <div class="button">
            <form action="" method="post"><button name="back">back to login page</button></form>
           
            <?php
            $kondisi_mboh = false;
            echo "<form action='' method='post'>";
            echo "<button name='editmaybe' >edit mode</button>";
            echo "<button name='liatmaybe' >view mode</button>";
            echo "</form> "; ?>
        </div>
    </section>
    <main>
        <?php
        ////// buat include dan mentalin masuk tanpa login
        include 'db.php';
        if (!$_SESSION['login']) {
            header("location:login.php");
        };

        ///// manggil data dari database untuk di tampilkan di table
        $SQL_in = "SELECT*FROM bobok ORDER BY id DESC";
        $hasil = $conne->query($SQL_in);

        /////buat pulang ke laman login
        if (isset($_POST['back'])) {
            header("location:login.php");
        }

        ///////untuk menambahkan data ke database 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add'])) {
                $inp_tanggal = htmlspecialchars(@$_POST['nama']);
                $inp_T_siang= intval(htmlspecialchars(@$_POST['stok']));
                $inp_T_malam= strval(htmlspecialchars(@$_POST['status']));
                $p_kualitas =strval(htmlspecialchars($_POST['kualitas']));
                $ngantuk_in=strval(htmlspecialchars($_POST['ngantukan']));
                $SQL_out =" INSERT INTO `bobok`(`tanggal`, `lama_tidur_siang`, `lama_tidur_malam`, `perkiraan_kualitas`, `ngantuk`) VALUES ('$inp_tanggal','$inp_T_siang','$inp_T_malam','$p_kualitas','$ngantuk_in')";
                $conne->query($SQL_out);
                $conne->close();
                header('Location: ' . $_SERVER["PHP_SELF"], true, 303);
            }
        };

        ////////////////////////edit mode???
        $kondisi_mboh = false;
        if (isset($_POST['editmaybe'])) {
            $kondisi_mboh = true;
        }
        if (isset($_POST['liatmaybe'])) {
            $kondisi_mboh = true;
        }

        //// buat reset negecegah eror
        $inp_barang = null;
        $inp_jum = null;
        $inp_stat = null;
        // ///////////////////////////
        ?>

        <table>
            <tr>
                <th>no</th>
                <th>tanggal</th>
                <th>lama tidur siang</th>
                <th>lama tidur malam</th>
                <th>perkiraan kualitas</th>
                <th>ngantuk</th>
                <th>aksi</th>
            </tr>
            <?php

            //////perulangan untuk menampilkan data di table///////////////////////////////////////////////////////////
            while ($baris = $hasil->fetch_row()) {
                if ($baris != null) {
                    $inilah[] = $baris; // Add the row to the array
                }
            }

            if (!$kondisi_mboh) {
                jalan($inilah); // Call function
            } elseif ($kondisi_mboh) {
                mundur($inilah);
            }
            function jalan($inilah) //function to echo the data
            {
                $inilah = array_reverse($inilah);
                $indxing = 1;

                foreach ($inilah as $baris) {
                    echo '<tr>';
                    echo '<th>' . $indxing  . '</th>';
                    echo '<th>' . $baris[1] . ' </th>';
                    echo '<th>' . $baris[2] . ' </th>';
                    echo '<th>' . $baris[3] . ' </th>';
                    echo '<th>' . $baris[4] . ' </th>';
                    echo '<th>' . $baris[5] . ' </th>';
                    echo '<th><form method="POST" action="">';
                    echo '<button type="submit" name="btt" value="' . htmlspecialchars($baris[0]) . '">remove</button>';
                    echo '</form></th>';
                    echo '</tr>';
                    $indxing += 1;
                };
                // ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



                // ntuk nge delete data dari database/////////////////////////
                if (isset($_POST['btt'])) {
                    $idToDelete = (int)$_POST['btt']; // Cast to int for safety
                    $conne2 = new mysqli("localhost", "root", "", "jadwal_tidur");

                    $stmt = $conne2->prepare("DELETE FROM bobok WHERE id = ?");
                    $stmt->bind_param("i", $idToDelete); // "i" indicates the type is integer
                    $stmt->execute();
                    $stmt->close();
                    $conne2->close();
                };

                // //////////////untuk edit data//////////
                if (isset($_POST['edit'])) {
                    $idToedit = (int)$_POST['edit']; // Cast to int for safety
                    $conne3 = new mysqli("localhost", "root", "", "jadwal_tidur");

                    $tanggal_edit = @$_POST['tanggal_edit'];
                    $lama_tidur_siang = @$_POST['lama_tidur_siang'];
                    $lama_tidur_malam = @$_POST['lama_tidur_malam'];
                    $perkiraan_kualitas_edit = @$_POST['kualitas_edit'];
                    $ngantuk_edit = @$_POST['ngantuk_edit'];
                    $stmtn = $conne3->prepare("UPDATE bobok SET `tanggal`='$tanggal_edit' , `lama_tidur_siang`='$lama_tidur_siang' , `lama_tidur_malam`='$lama_tidur_malam' ,`perkiraan_kualitas`='$perkiraan_kualitas_edit' , `ngantuk`='$ngantuk_edit' WHERE id = ?");
                    $stmtn->bind_param("i", $idToedit); // "i" indicates the type is integer
                    $stmtn->execute();
                    $stmtn->close();
                    $conne3->close();
                    header('Location: ' . $_SERVER["PHP_SELF"], true, 303);
                }
            }


            function mundur($inilah)
            {
                $inilah = array_reverse($inilah);
                $indxing = 1;
                echo "only 1 at the time";
                foreach ($inilah as $baris) {
                    $mbohco = $baris['3'];
                    echo '<tr>';
                    echo '<th> <form method="POST" action="">' . $indxing  . '</th>';
                    echo '<th><input value=' . $baris[1] . ' name="tanggal_edit"></input></th>';
                    echo '<th><input value=' . $baris[2] . ' name="lama_tidur_siang"></input></th>';
                    echo '<th><input value=' . $baris[3] . ' name="lama_tidur_malam"></input></th>';
                    echo '<th><input value=' . $baris[4] . ' name="perkiraan_kualitas"></input></th>';
                    echo "<th><select name='lama_tidur_malam'>";
                    echo '<option value="iya"'; ?> <?= ($baris['5'] == 'iya') ? 'selected' : '' ?> <?php echo '> iya </option>';
                                                                                    echo '<option value="engga" '; ?><?= ($mbohco == 'engga') ? 'selected' : '' ?><?php echo '>engga</option>';
                                                                                    echo '</select></th>';
                                                                                    echo '<th><button type="submit" name="edit" value="' . htmlspecialchars($baris[0]) . '">edit</button>';
                                                                                    echo '</form></th>';
                                                                                    echo '</tr>';
                                                                                    $indxing += 1;
                                                                                };
                                                                            }
                                                                                    ?>
                    <tr>
                        <form action="index.php" method="post">
                            <td>?</td>
                            <td><input type="text" name="nama"></td>
                            <td><input type="text" name="stok" id=""></td>
                            <td><input type="text" name="status" id=""></td>
                            <td><input type="text" name="kualitas" id=""></td>
                            <td><select name="ngantukan" id="">
                                    <option value="iya">iya</option>
                                    <option value="engga">engga</option>
                                </select></td>
                            <td><button name="add" type="submit" value="">adding.</button></td>
                        </form>
                    </tr>
        </table>
    </main>
    <script>
        document.getElementById('to_user').addEventListener("click", function() {
            window.location.href = "user.php";
        });

        // function cobain_edit(){
        //     $("th").replaceWith("<input>" + $("th").html() + "</input>");
        // }
    </script>
</body>

</html>