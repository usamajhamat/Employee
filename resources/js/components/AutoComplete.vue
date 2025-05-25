<script setup lang="ts">
import { ref } from "vue";
import { Check, ChevronsUpDown } from "lucide-vue-next";

import { cn } from "@/lib/utils";
import { Button } from "@/components/ui/button";
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from "@/components/ui/command";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/components/ui/popover";

const props = defineProps({
    options: {
        type: Array,
        default: () => [],
    },
    placeholder: {
        type: String,
        default: "",
    },
});

const emit = defineEmits(["update:selectedOption"]);

const open = ref(false);
const value = ref("");
const options = ref([]);

function handleInput(event) {
    clearSelection();

    if (event.target.value !== "") {
        options.value = props.options
            .filter((option) =>
                option.label.toLowerCase().includes(event.target.value)
            )
            .map((option) => ({
                value: option.value,
                label: option.label,
            }));
    } else {
        options.value = [];
        value.value = "";
        clearSelection();
    }
}

function handleSelect(option) {
    value.value = option.value;
    open.value = false;
    emit("update:selectedOption", option.value);
}

function clearSelection() {
    value.value = "";
}
</script>

<template>
    <Popover v-model:open="open">
        <PopoverTrigger as-child>
            <Button
                variant="outline"
                role="combobox"
                :aria-expanded="open"
                class="w-full justify-between"
            >
                {{
                    value
                        ? options.find((option) => option.value === value)
                              ?.label
                        : `Select  ${props.placeholder}  ...`
                }}
                <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-[--radix-popover-trigger-width] max-h-[--radix-popover-content-available-height] p-0">
            <Command>
                <CommandInput
                    @input="handleInput"
                    class="h-9"
                    :placeholder="'Search ' + props.placeholder + ' ...'"
                />
                <CommandEmpty>Nothing found.</CommandEmpty>
                <CommandList>
                    <CommandGroup>
                        <CommandItem
                            v-for="option in options"
                            :key="option.value"
                            :value="option.value"
                            @select="(ev) => handleSelect(option)"
                        >
                            {{ option.label }}
                            <Check
                                :class="
                                    cn(
                                        'ml-auto h-4 w-4',
                                        value === option.value
                                            ? 'opacity-100'
                                            : 'opacity-0'
                                    )
                                "
                            />
                        </CommandItem>
                    </CommandGroup>
                </CommandList>
            </Command>
        </PopoverContent>
    </Popover>
</template>
