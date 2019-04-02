<style>
	svg{
		margin: 20px;
		display:inline-block;
	}

	.nhlstats--wrapper {
		display: flex;
		flex-wrap: wrap;
		justify-content: center;
		align-items: center;
		text-align: center;
		width: 100%;
	}
</style>
<div class="nhlstats--wrapper">
	<div>
		<svg version="1.1" id="L5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 60 70" enable-background="new 0 0 0 0" xml:space="preserve">
			<circle fill="#23282d" stroke="none" cx="6" cy="50" r="6" transform="translate(0 -13.9602)">
				<animateTransform attributeName="transform" dur="1s" type="translate" values="0 15 ; 0 -15; 0 15" repeatCount="indefinite" begin="0.1"></animateTransform>
			</circle>
			<circle fill="#23282d" stroke="none" cx="30" cy="50" r="6" transform="translate(0 -5.3068)">
				<animateTransform attributeName="transform" dur="1s" type="translate" values="0 10 ; 0 -10; 0 10" repeatCount="indefinite" begin="0.2"></animateTransform>
			</circle>
			<circle fill="#23282d" stroke="none" cx="54" cy="50" r="6" transform="translate(0 -0.6534)">
				<animateTransform attributeName="transform" dur="1s" type="translate" values="0 5 ; 0 -5; 0 5" repeatCount="indefinite" begin="0.3"></animateTransform>
			</circle>
		</svg>
		<h1><?php _e( 'Loading the data', 'nhlstats' ); ?></h1>
	</div>
</div>
<script>
	checkTransient();
</script>