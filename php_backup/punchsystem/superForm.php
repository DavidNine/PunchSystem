<?php
    $admin_acc = $_POST['account'];
    $admin_pas = $_POST['password'];

    /* admin chcek */
    if ($admin_acc != 'admin' || $admin_pas != 'admin123')
    {
        echo "
        <script>
            alert('account or password error, login fail.');
            window.history.back();
        </script>
        ";
    }
    else 
    {
        echo "
        <script>
            alert('Login successful');
            window.location.href = 'superHome.php?&account=$admin_acc';
        </script>
        ";
    }
?>
