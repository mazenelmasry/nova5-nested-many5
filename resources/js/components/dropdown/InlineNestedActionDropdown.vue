<template>
    <div v-if="shouldShowDropdown">
        <Dropdown class="h-9">
            <slot name="trigger">
                <Button variant="action" icon="ellipsis-horizontal" :disabled="!!runningAction" />
            </slot>
            <template #menu>
                <DropdownMenu width="auto" class="px-1">
                    <ScrollWrap :height="250" class="divide-y divide-gray-100 dark:divide-gray-800 divide-solid">
                        <div v-if="actions.length > 0" class="py-1">
                            <!-- User Actions -->
                            <DropdownMenuItem
                                class="border-none"
                                as="button"
                                v-for="action in actions"
                                :data-action-id="action.uriKey"
                                :key="action.uriKey"
                                @click="$emit('run-action', action.uriKey)"
                                :title="action.name"
                                :destructive="action.destructive"
                                :disabled="!!runningAction"
                            >
                                {{ action.name }}
                            </DropdownMenuItem>
                        </div>
                    </ScrollWrap>
                </DropdownMenu>
            </template>
        </Dropdown>
    </div>
</template>

<script>
    import { Button } from 'laravel-nova-ui';

    export default {
        components: { Button },
        props: {
            runningAction: {
                type: String,
                required: true,
            },
            actions: { type: Array },
        },

        data: () => ({}),

        computed: {
            currentTrashed() {
                return '';
            },

            shouldShowDropdown() {
                return this.actions.length > 0;
            },
        },
    };
</script>
