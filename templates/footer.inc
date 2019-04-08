		</main>
		<footer id="footer" class="py-2">
			<p class="text-center text-white my-2">Copyright &copy; 2017-<?=date('Y')?> <a href="https://myrotvorets.center/">Myrotvorets Research Center</a></p>
		</footer>
	</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha256-CjSoeELFOcH0/uxWu6mC/Vlrc1AARqbm/jiiImDGV3s=" crossorigin="anonymous"></script>
<?php if (!empty($footer_js) && is_array($footer_js)) : ?>
<?php foreach ($footer_js as $src) : ?>
	<script src="<?=$src;?>"></script>
<?php endforeach; ?>
<?php endif; ?>
<script>
var uri = window.location.toString();
if (uri.indexOf("?") > 0) {
	var clean_uri = uri.substring(0, uri.indexOf("?"));
	window.history.replaceState({}, document.title, clean_uri);
}
</script>
</body>
</html>

