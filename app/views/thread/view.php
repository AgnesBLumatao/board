<table width="100%">
	<tr>
		<td>
		    <h1><?php eh($thread->title) ?></h1>
		</td>
		
		<td align="right">
		    <h5>WELCOME <?php eh($username) ?>
                <a class="btn btn-small btn-primary" href="<?php eh(url('thread/index')) ?>">LOG OUT</a>
            </h5>
		</td>
	</tr>
	
	<tr align="right">
		<td>
		</td>
		
		<td>
            <a href="<?php eh(url('thread/home', array('page'=>1, 'user_id' => $user_id, 'username'=>$username))) ?>">
            back to thread
            </a>
		</td>
	</tr>
</table>

<br />
<hr>

<h5>
    PAGE <?php eh($page) ?> OF <?php
    if ($totalPage > 0) :
        eh($totalPage);
    else :
        eh('1');
    endif ?>
</h5>

<?php
//show all comments
for ($x = 0;$x < $totalComment;$x++) : ?>
	<div class="comment">
	
		<div class="meta">
		    <?php eh(($previousPage) * Thread::THREAD_COMMENT_LIMIT + ($x + 1)) ?>:<?php eh($comments[$x]['created']) ?>
		</div>
		
		<div>
		    <?php eh($comments[$x]['body']) ?>
		</div>
	<br />
	</div>
<?php
endfor;

//pagination
if ($totalPage > 1) : ?>
    <table>
        <tr>
            <?php
            //link to previous 10 pages
            if ($start != 1) : ?>
                <td>
                    <a href="<?php eh(url('thread/view', array('page'=>$previous, 'thread_id' => $thread->id, 'username'=>$username, 'user_id'=>$user_id))) ?>">
                    &larr; previous
                    </a>
                </td>
            <?php
            endif;

            //links for page number
            for ($x = $start;$x < $nums;$x++) :
                if($x != $page) : ?>
                    <td>
                        <a href="<?php eh(url('thread/view', array('page'=>$x, 'thread_id' => $thread->id, 'username'=>$username, 'user_id'=>$user_id))) ?>">
                        <?php eh($x) ?>
                        </a>
                    </td>
                <?php
                else : ?>
                    <td>
                        <?php eh($x) ?>
                    </td>
                <?php
                endif;
            endfor;

            //link to next 10 pages
            if ($lastPage < $totalPage) : ?>
                <td>
                    <a href="<?php eh(url('thread/view', array('page'=>$next, 'thread_id' => $thread->id, 'username'=>$username, 'user_id'=>$user_id))) ?>">
                    next &rarr;
                    </a>
                </td>
            <?php
            endif ?>

        </tr>
    </table>
<?php
endif ?>

<hr>
<form class="well" method="post" action="<?php eh(url('thread/write', array( 'page'=>0, 'user_id'=>$user_id, 'username'=>$username))) ?>">
	<label>Comment</label>
	<textarea name="body"><?php eh(Param::get('body')) ?></textarea>
	
	<br />
	<input type="hidden" name="thread_id" value="<?php eh($thread->id) ?>">
	<input type="hidden" name="page_next" value="write_end">
	<button type="submit" class="btn btn-primary">Submit</button>
</form>