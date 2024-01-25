<?php

if ( ! defined( 'ABSPATH' ) || ! isset( $data ) ) {
	return '';
}
?>
<div class="offer">
	<div class="offer__content">
		<div class="offer__column">
			<img class="offer__logo"
				 src="<?php echo $data['logo']; ?>"
				 title="<?php echo $data['brand']; ?>"
				 alt="<?php echo $data['alt']; ?>"/>
		</div>
		<div class="offer__column">
			<?php
			if ( ! empty( $data['headlines'] ) ) {
				foreach ( $data['headlines'] as $item ) {
					?>
					<div
						class="offer__headlines offer__headlines_<?php echo $item['name']; ?>"><?php echo $item['value']; ?></div>
					<?php
				}
			}
			?>
		</div>
		<div class="offer__column">
			<div class="offer__row">
				<div class="rating">
					<?php
					if ( ! empty( $data['stars'] ) ) {
						foreach ( $data['stars'] as $value ) {
							?>
							<div class="rating__star rating__star_<?php echo $value; ?>"></div>
							<?php
						}
					}
					?>
				</div>
			</div>
			<div class="offer__row">
				<?php
				// START: if $data['deposits']
				if ( ! empty( $data['deposits']['slides'] ) ) {
					?>

					<div class="carousel js-carousel">
						<div class="carousel__box">
							<div class="carousel__container js-carousel-container">
								<?php
								// START: loop $data['deposits']
								foreach ( $data['deposits']['slides']['items'] as $index => $slide ) {
									?>
									<div class="carousel__slide js-carousel-slide" data-index="<?php echo $index; ?>">
										<?php
										// START: if $slide
										if ( ! empty( $slide['items'] ) ) {
											// START: loop $slide
											foreach ( $slide['items'] as $image ) {
												?>
												<div class="carousel__item">
													<img class="carousel__image"
														 src="<?php echo $image['src']; ?>"
														 alt="<?php echo $image['name']; ?>"/>
												</div>
												<?php
											} // END: loop $slide
										} // END: if $slide
										?>
									</div>
									<?php
								} // START: loop $data['deposits']
								?>
							</div>
						</div>
						<div class="carousel__bullets">
							<?php
							if ( ! empty( $data['deposits']['bullets'] ) ) {
								foreach ( $data['deposits']['bullets'] as $item ) {
									?>
									<div class="carousel__bullet<?php echo $item['mixin']; ?> js-carousel-bullet"
										 data-slide="<?php echo $item['index']; ?>"></div>
									<?php
								}
							}
							?>
						</div>
					</div>
					<?php
				} // END: if $data['deposits']
				?>
			</div>
		</div>
		<div class="offer__column offer__column_small">
			<?php
			if ( ! empty( $data['bullet_points'] ) ) {
				?>
				<ul class="offer__points-list">
					<?php
					if ( ! empty( $data['bullet_points']['items'] ) ) {
						foreach ( $data['bullet_points']['items'] as $value ) {
							?>
							<li class="offer__point"><?php echo $value; ?></li>
							<?php
						}
					}
					?>
				</ul>
				<?php
			}
			?>
		</div>
		<div class="offer__column">
			<?php
			if ( ! empty( $data['cta_link'] ) && ! empty( $data['cta'] ) ) {
				?>
				<div class="offer__row">
					<a href="<?php echo $data['cta_link']; ?>"
					   target="_blank"
					   class="button button_cta"><?php echo $data['cta']; ?></a>
				</div>
				<?php
			}
			?>
			<div class="offer__row offer__row_small">
				<a class="offer__terms" target="_blank"
				   href="<?php echo $data['terms_link']; ?>"><?php echo $data['terms']; ?></a>
				<div class="offer__separator"></div>
				<a class="offer__review"
				   target="_blank"
				   href="<?php echo $data['review_link']; ?>"><?php echo $data['review']; ?></a>
			</div>

		</div>
		<div class="offer__absolute">
			<?php
			if ( ! empty( $data['preview_image'] ) ) {
				?>
				<div class="offer__preview">
					<button type="button" class="button button_preview js-offer-preview"
							data-src="<?php echo $data['preview_image']; ?>"><?php echo $data['preview_text']; ?></button>
				</div>
				<?php
			}
			?>
			<?php
			if ( ! empty( $data['ribbon'] ) ) {
				?>
				<div class="offer__ribbon"><?php echo $data['ribbon']; ?></div>
				<?php
			}
			?>
		</div>
	</div>
	<?php
	if ( ! empty( $data['show_footer'] ) ) {
		?>
		<div class="offer__footer">
			<div class="offer__row">
				<div class="offer__fine-print"><?php echo $data['fine_print']; ?></div>
				<div class="offer__separator"></div>
				<div class="offer__disclaimer"><?php echo $data['disclaimer']; ?></div>
			</div>
		</div>
		<?php
	}
	?>

</div>

