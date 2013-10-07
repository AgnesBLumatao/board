<table width="100%">
    <tr>
        <td>
            <h1>ALL THREADS</h1>
        </td>
        
        <td align="right">
            <h5>WELCOME <?php eh($user['username']) ?>
                <a class="btn btn-small btn-primary" href="<?php eh(url('thread/index')) ?>">LOG OUT</a>
            </h5>
        </td>
    </tr>
</table>

<br />
<h5>
    PAGE <?php eh($page) ?> OF
    <?php
        if ($totalPage > 0) :
            eh($totalPage);
        else :
            eh('1');
        endif ?>
</h5>

<ul>
<?php
    //show all threads
    if ($totalThread > 0) :
        for ($x = 0; $x < $totalThread; $x++) : ?>
            <li>
                <a href="<?php eh(url('thread/view', array('page'=>1, 'thread_id' => $threads[$x]['id'], 'user_id'=>$user['user_id'], 'username'=>$user['username']))) ?>">
                <?php eh($threads[$x]['title']) ?>
                </a>
            </li>
<?php    endfor;
    endif ?>
</ul>

<?php
//pagination
if ($totalPage > 1) : ?>
    <table>
        <tr>
        <?php
        //link to previous 10 pages
        if ($start != 1) : ?>
            <td>
                <a href="<?php eh(url('thread/home', array('page'=>$previous, 'user_id' => $user['user_id'], 'username'=>$user['username']))) ?>">
                &larr; previous
                </a>
            </td>
        <?php
        endif;

        //links for page number
        for ($x = $start;$x < $nums;$x++) :
            if ($x != $page) : ?>
                <td>
                    <a href="<?php eh(url('thread/home', array( 'page'=>$x, 'user_id' => $user['user_id'], 'username'=>$user['username']))) ?>">
                    <?php eh($x) ?>
                    </a>
                </td>
            <?php
            else  : ?>
                <td>
                    <?php eh($x) ?>
                </td>
        <?php
            endif;
        endfor;

        //link to next 10 pages
        if ($lastPage < $totalPage) : ?>
            <td>
                <a href="<?php eh(url('thread/home', array('page'=>$next, 'user_id' => $user['user_id'], 'username'=>$user['username']))) ?>">
                next &rarr;
                </a>
            </td>
        <?php endif ?>

        </tr>
    </table>
<?php
endif ?>

<a class="btn btn-large btn-primary" href="<?php eh(url('thread/create', array('user_id'=>$user['user_id'], 'username'=>$user['username']))) ?>">Create</a>