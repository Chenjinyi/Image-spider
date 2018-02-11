/**
 * Author: Luigi Petrut
 * Author URI: http://tutsmix.com/
 * Version: 1.0
 */
jQuery(document)
    .ready(function($) {
        // Replace "tada" with an effect from the "effects.css" file.
        var effect = 'animate tada';

        var masonry_selector = '.masonry';

        var masonry_item_selector = '.masonry-item';

        // Initialize Masonry.
        var $masonry = $(masonry_selector)
            .masonry({
                itemSelector: masonry_item_selector
            });

        // Find and hide the items.
        var $masonry_items = $masonry
            .find(masonry_item_selector)
            .hide();

        // Wait for the images to load.
        $masonry
            .imagesLoaded()
            // An image has been loaded.
            .progress(function(instance, image) {
                // Add the effect.
                var $image = $(image.img)
                    .addClass(effect);

                // Find and show the item.
                var $item = $image
                    .parents(masonry_item_selector)
                    .show();

                // Lay out Masonry.
                $masonry
                    .masonry();
            });

        // Load more items.
        $masonry
            .infinitescroll({
                navSelector: '.pagination',
                nextSelector: '.pagination .next',
                itemSelector: masonry_item_selector,
                loading: {
                    finishedMsg: 'No more pages to load.',
                    img: 'images/loader.gif',
                    msgText: 'Loading the next page.',
                    selector: '.loading'
                }
            }, function(items, data, url) {
                var $items = $(items)
                    .hide()
                    .imagesLoaded()
                    .progress(function(instance, image) {
                        var $image = $(image.img)
                            .addClass(effect);

                        var $item = $image
                            .parents(masonry_item_selector)
                            .addClass('infinite-scroll-item')
                            .show();

                        $masonry
                            .masonry('appended', $item);
                    });
            });

        /**
         * Change the effect.
         *
         * Please note that you should remove the code below because it's required only for the demo.
         */
        $('select[name="effects"]')
            .on('change', function() {
                var old_effect = effect;

                // Set the new effect.
                effect = 'animate ' + $(this).val();

                // Remove the appended items.
                $masonry
                    .find('.infinite-scroll-item')
                    .remove();

                // Show the pagination.
                $('.pagination')
                    .show();

                // Empty the ".loading" container.
                $('.loading')
                    .empty();

                // Remove the previous effect and add the new one.
                $masonry
                    .find('img')
                    .removeClass(old_effect)
                    .addClass(effect);

                // Lay out Masonry.
                $masonry
                    .masonry();

                // Reset Infinite Scroll.
                $masonry
                    .infinitescroll('destroy')
                    .data('infinitescroll', null);

                // Load more items.
                $masonry
                    .infinitescroll({
                        navSelector: '.pagination',
                        nextSelector: '.pagination .next',
                        itemSelector: masonry_item_selector,
                        loading: {
                            finishedMsg: 'No more pages to load.',
                            img: 'images/loader.gif',
                            msgText: 'Loading the next page.',
                            selector: '.loading'
                        }
                    }, function(items, data, url) {
                        var $items = $(items)
                            .hide()
                            .imagesLoaded()
                            .progress(function(instance, image) {
                                var $image = $(image.img)
                                    .addClass(effect);

                                var $item = $image
                                    .parents(masonry_item_selector)
                                    .addClass('infinite-scroll-item')
                                    .show();

                                $masonry
                                    .masonry('appended', $item);
                            });
                    });
            });
			
			
			
			
    });
