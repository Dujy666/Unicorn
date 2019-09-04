<?php
    $nickname = $_POST['nickname'];
    $password = $_POST['repass'];
    $question = $_POST['safe_question'];
    $answer = $_POST['safe_answer'];
    $student_id = $_POST['student_id'];

    include_once 'conn/conn.php';

    $insert_mysql = "insert into tb_user(nickname,pass,safe_question,safe_answer,student_id) values('$nickname','$password','$question','$answer','$student_id')";

    $result = $conn->query($insert_mysql);
    $key ="ALTER  TABLE  tb_user DROP id"; //舍弃原来的主键id
    $result1 =  $conn->query($key);
    $key_do = "ALTER  TABLE  tb_user ADD id mediumint(6) PRIMARY KEY NOT NULL AUTO_INCREMENT FIRST";//新建一个主键id，并重新排列
    $result2 =  $conn->query($key_do);

    if($result&&$result1&&$result2){
        $find_id = "select id from tb_user where student_id=$student_id";
        $result3 = $conn->query($find_id);
        $id = mysqli_fetch_row($result3);
        $seed = time();                   // 使用时间作为种子源
        srand($seed);                     // 播下随机数发生器种子

        $id1 = rand(10000000,99999999);  // 从这个范围内取出一个数
        $id2 = rand(10000000,99999999);  // 从这个范围内取出一个数
        $uni_id = substr($id[0] .$id1.$id2,0,8);   // 连接从数据库中取出的唯一id号和上面取出的两个随机数，并截断为8位，因为开头的id唯一，所以整个8位数字也唯一。

        $insert_uni_id = "update  tb_user set uni_id='$uni_id' where student_id=$student_id ";
        $result4 = $conn->query($insert_uni_id);
        if($result4){
            echo "注册成功！你的ID是$uni_id,请牢记。";
        }else{
            echo "生成ID失败";
        }
    }else{
        echo "注册失败";
    }