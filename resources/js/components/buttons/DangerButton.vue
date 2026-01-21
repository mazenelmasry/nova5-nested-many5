<template>
    <Button
        v-bind="$attrs"
        :as="component"
        :size="buttonSize"
        variant="solid"
        state="danger"
        ref="button"
        class="shadow relative"
        :class="alignClass"
    >
        <slot />
    </Button>
</template>
<script>
    import { Button } from 'laravel-nova-ui';

    export default {
        components: { Button },
        props: {
            size: {
                type: String,
                default: 'lg',
            },

            align: {
                type: String,
                default: 'center',
                validator: v => ['left', 'center'].includes(v),
            },

            component: {
                type: String,
                default: 'button',
            },
        },

        computed: {
            buttonSize() {
                return this.size === 'sm' ? 'small' : 'large';
            },

            alignClass() {
                return this.align === 'left' ? 'justify-start' : 'justify-center';
            },
        },

        methods: {
            focus() {
                this.$refs.button.focus();
            },
        },
    };
</script>
