<?php
    use Filament\Support\Facades\FilamentView;
    use Saade\FilamentAutograph\Forms\Components\Enums\DownloadableFormat;
?>

<?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => $getFieldWrapperView()] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => $field]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <?php
        $isDisabled = $isDisabled();
        $isClearable = $isClearable();
        $isDownloadable = $isDownloadable();
        $downloadableFormats = $getDownloadableFormats();
        $downloadActionDropdownPlacement = $getDownloadActionDropdownPlacement() ?? 'bottom-start';
        $isUndoable = $isUndoable();
        $isConfirmable = $isConfirmable();
        $loadStrategy = $getLoadStrategy();

        $clearAction = $getAction('clear');
        $downloadAction = $getAction('download');
        $undoAction = $getAction('undo');
        $doneAction = $getAction('done');
    ?>

    <div
        x-load="visible || event (ax-modal-opened)"
        x-load-src="<?php echo e(\Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-autograph-alpine', 'saade/filament-autograph')); ?>"
        x-data="signaturePadFormComponent({
            backgroundColor: <?php echo \Illuminate\Support\Js::from($getBackgroundColor())->toHtml() ?>,
            backgroundColorOnDark: <?php echo \Illuminate\Support\Js::from($getBackgroundColorOnDark())->toHtml() ?>,
            confirmable: <?php echo \Illuminate\Support\Js::from($isConfirmable)->toHtml() ?>,
            disabled: <?php echo \Illuminate\Support\Js::from($isDisabled)->toHtml() ?>,
            dotSize: <?php echo e($getDotSize()); ?>,
            exportBackgroundColor: <?php echo \Illuminate\Support\Js::from($getExportBackgroundColor())->toHtml() ?>,
            exportPenColor: <?php echo \Illuminate\Support\Js::from($getExportPenColor())->toHtml() ?>,
            filename: '<?php echo e($getFilename()); ?>',
            maxWidth: <?php echo e($getLineMaxWidth()); ?>,
            minDistance: <?php echo e($getMinDistance()); ?>,
            minWidth: <?php echo e($getLineMinWidth()); ?>,
            penColor: <?php echo \Illuminate\Support\Js::from($getPenColor())->toHtml() ?>,
            penColorOnDark: <?php echo \Illuminate\Support\Js::from($getPenColorOnDark())->toHtml() ?>,
            state: $wire.<?php echo e($applyStateBindingModifiers("\$entangle('{$getStatePath()}')")); ?>,
            throttle: <?php echo e($getThrottle()); ?>,
            velocityFilterWeight: <?php echo e($getVelocityFilterWeight()); ?>,
        })"
    >
        <canvas
            x-ref="canvas"
            wire:ignore
            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                'w-full h-36 rounded-lg border border-gray-300',
                'dark:bg-gray-900 dark:border-white/10',
                'opacity-75 bg-gray-50' => $isDisabled,
            ]); ?>"
        ></canvas>

        <div class="flex items-center justify-end mt-3 space-x-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isClearable): ?>
                <?php echo e($clearAction); ?>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isUndoable): ?>
                <?php echo e($undoAction); ?>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isDownloadable): ?>
                <?php if (isset($component)) { $__componentOriginal22ab0dbc2c6619d5954111bba06f01db = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal22ab0dbc2c6619d5954111bba06f01db = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.dropdown.index','data' => ['placement' => ''.e($downloadActionDropdownPlacement).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placement' => ''.e($downloadActionDropdownPlacement).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                     <?php $__env->slot('trigger', null, []); ?> 
                        <?php echo e($downloadAction); ?>

                     <?php $__env->endSlot(); ?>

                    <?php if (isset($component)) { $__componentOriginal66687bf0670b9e16f61e667468dc8983 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal66687bf0670b9e16f61e667468dc8983 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.dropdown.list.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::dropdown.list'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array(DownloadableFormat::PNG, $downloadableFormats)): ?>
                            <?php if (isset($component)) { $__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.dropdown.list.item','data' => ['xOn:click' => 'downloadAs(\''.e(DownloadableFormat::PNG->getMime()).'\', \''.e(DownloadableFormat::PNG->getExtension()).'\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::dropdown.list.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-on:click' => 'downloadAs(\''.e(DownloadableFormat::PNG->getMime()).'\', \''.e(DownloadableFormat::PNG->getExtension()).'\')']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                <?php echo e(DownloadableFormat::PNG->getLabel()); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78)): ?>
<?php $attributes = $__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78; ?>
<?php unset($__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78)): ?>
<?php $component = $__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78; ?>
<?php unset($__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78); ?>
<?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array(DownloadableFormat::JPG, $downloadableFormats)): ?>
                            <?php if (isset($component)) { $__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.dropdown.list.item','data' => ['xOn:click' => 'downloadAs(\''.e(DownloadableFormat::JPG->getMime()).'\', \''.e(DownloadableFormat::JPG->getExtension()).'\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::dropdown.list.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-on:click' => 'downloadAs(\''.e(DownloadableFormat::JPG->getMime()).'\', \''.e(DownloadableFormat::JPG->getExtension()).'\')']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                <?php echo e(DownloadableFormat::JPG->getLabel()); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78)): ?>
<?php $attributes = $__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78; ?>
<?php unset($__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78)): ?>
<?php $component = $__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78; ?>
<?php unset($__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78); ?>
<?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array(DownloadableFormat::SVG, $downloadableFormats)): ?>
                            <?php if (isset($component)) { $__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.dropdown.list.item','data' => ['xOn:click' => 'downloadAs(\''.e(DownloadableFormat::SVG->getMime()).'\', \''.e(DownloadableFormat::SVG->getExtension()).'\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::dropdown.list.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-on:click' => 'downloadAs(\''.e(DownloadableFormat::SVG->getMime()).'\', \''.e(DownloadableFormat::SVG->getExtension()).'\')']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                <?php echo e(DownloadableFormat::SVG->getLabel()); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78)): ?>
<?php $attributes = $__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78; ?>
<?php unset($__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78)): ?>
<?php $component = $__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78; ?>
<?php unset($__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78); ?>
<?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal66687bf0670b9e16f61e667468dc8983)): ?>
<?php $attributes = $__attributesOriginal66687bf0670b9e16f61e667468dc8983; ?>
<?php unset($__attributesOriginal66687bf0670b9e16f61e667468dc8983); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal66687bf0670b9e16f61e667468dc8983)): ?>
<?php $component = $__componentOriginal66687bf0670b9e16f61e667468dc8983; ?>
<?php unset($__componentOriginal66687bf0670b9e16f61e667468dc8983); ?>
<?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal22ab0dbc2c6619d5954111bba06f01db)): ?>
<?php $attributes = $__attributesOriginal22ab0dbc2c6619d5954111bba06f01db; ?>
<?php unset($__attributesOriginal22ab0dbc2c6619d5954111bba06f01db); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal22ab0dbc2c6619d5954111bba06f01db)): ?>
<?php $component = $__componentOriginal22ab0dbc2c6619d5954111bba06f01db; ?>
<?php unset($__componentOriginal22ab0dbc2c6619d5954111bba06f01db); ?>
<?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isConfirmable): ?>
                <?php echo e($doneAction); ?>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $attributes = $__attributesOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $component = $__componentOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__componentOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php /**PATH C:\Users\Administrator\Herd\ojt-dtr-journal\vendor\saade\filament-autograph\resources\views/signature-pad.blade.php ENDPATH**/ ?>