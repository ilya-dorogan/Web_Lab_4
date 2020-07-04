<?php
	if (empty($_GET['article'])) {
		header('Location: index.php');
		exit();
	}
	require_once 'DBControl.php';
	$db = getDbConnection();
	$ID = SQLite3::escapeString($_GET["article"]);
	$article = $db->query("SELECT * FROM Posts where ID='{$ID}'")->fetchArray(SQLITE3_ASSOC);
	if (!$article) {
		http_response_code(404);
		exit('Not found');
	}
	$pageTitle = htmlspecialchars($article['Title']);

	$commentErrors = [];
	$commentAuthor = '';
	$commentRate = 5;
	$commentText = '';
	if (isset($_POST['action']) && 'add-comment' === $_POST['action']) {
		$commentAuthor = trim((string)$_POST['Author_Name']);
		$commentRate = (int)$_POST['Rate'];
		$commentText = trim((string)$_POST['comment']);
		$commentDate = date('Y-m-d H:i:s');

		if ('' === $commentAuthor)
			$commentErrors['Author_Name'] = 'Author name can not be empty';
		elseif (mb_strlen($commentAuthor) > 20)
			$commentErrors['Author_Name'] = 'Author name can not be more than 20 characters';

		if ($commentRate < 1 || $commentRate > 5)
			$commentErrors['Rate'] = 'Rate is invalid';

		if ($commentRate < 1)
			$commentErrors['Rate'] = 'Rate is invalid';

		if ('' === $commentText)
			$commentErrors['comment'] = 'Comment can not be empty';
		elseif (mb_strlen($commentText) < 3)
			$commentErrors['comment'] = 'Comment can not be less than 3 characters';
		elseif (mb_strlen($commentText) > 200)
			$commentErrors['comment'] = 'Comment can not be more than 200 characters';

		if (0 === count($commentErrors)) {
			$result = $db->exec(sprintf(
				"INSERT INTO Comments (Article_id, Author_Name, Rate, Comment, Created) VALUES ('%d', '%s', '%s', '%s', '%s')",
				$article['ID'], SQLite3::escapeString($commentAuthor), SQLite3::escapeString($commentRate), SQLite3::escapeString($commentText), $commentDate));

			if (false === $result) {
				http_response_code(500);
				exit('Database insert error');
			}

			header("Location: article.php?article={$article['ID']}");
			exit();
		}
	}

	$comments = $db->query("SELECT * from Comments WHERE Article_id='{$article['ID']}'");
	if (false === $comments) {
		http_response_code(500);
		exit('Database query error');
	}
?>

<?php require 'head.php'; ?>
<?php require 'header.php'; ?>

<main class="site__centr">
	<div class="site__latest"><h3>Articles</h3></div>

	<div class="site__line"></div>

	<article class="article">
		<div class="article__body">
			<header>
				<time datetime="<?= $article['Created'] ?>" class="article__time"><?= $article['Created'] ?></time>
				<h1 class="article__title">
					<a href="article.php?article=<?= $article['ID']; ?>"><?= $pageTitle; ?></a>
				</h1>
			</header>
			<div class="article__text"><?= $article['Content'] ?></div>
		</div>
	</article>

	<div class="site__separator"></div>
	<div class="site__latest">You can leave comments here</div>
	<div class="site__separator"></div>

	<div class="article__info">
		<form action="" method="post">
			<input type="hidden" name="action" value="add-comment">
			<label>Name: <input type="text" name="Author_Name" value="<?= htmlspecialchars($commentAuthor); ?>"></label>
			<label>Article rate:
				<select name="Rate">
					<?php for ($i = 5; $i > 0; $i--) { ?>
						<option value="<?= $i; ?>"
								<?php if ($i === $commentRate) { ?>selected<?php } ?>
						>Rate <?= $i; ?></option>
					<?php } ?>
				</select>
			</label>

			<?php if (isset($commentErrors['Author_Name'])) { ?>
				<div class="comments__error"><?= $commentErrors['Author_Name']; ?></div>
			<?php } ?>
			<?php if (isset($commentErrors['Rate'])) { ?>
				<div class="comments__error"><?= $commentErrors['Rate']; ?></div>
			<?php } ?>
			<label>
				<textarea name="comment" cols="62" rows="10"><?= htmlspecialchars($commentText); ?></textarea>
			</label>
			<?php if (isset($commentErrors['comment'])) { ?>
				<div class="comments__error"><?= $commentErrors['comment']; ?></div>
			<?php } ?>
			<input class="comments__send" type="submit" value="Send">
		</form>
	</div>

	<div class="site__separator"></div>
	<div class="site__latest">Comments</div>
	<div class="site__separator"></div>

	<div class="article__comments">
		<?php while ($row = $comments->fetchArray(SQLITE3_ASSOC)) { ?>
			<div class="article__comment">
				<div class="comment__author">Author: <?= htmlspecialchars($row['Author_Name']); ?></div>
				<div class="comment__rate">Rate: <?= $row['Rate']; ?></div>
				<div class="comment__date">
					Published on
					<time datetime="<?= $row['Created']; ?>">
						<?= date('D, d M Y', strtotime($row['Created'])); ?>
					</time>
				</div>
				<div class="comment__text"><?= nl2br(htmlspecialchars($row['Comment'])); ?></div>
			</div>
		<?php } ?>
	</div>
</main>

<?php require 'footer.php'; ?>