<template>
  <div>
    <h1 class="mb-8 text-3xl font-bold">Benefits</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter
        v-model="form.search"
        class="w-full max-w-md mr-4"
        @reset="reset"
      ></search-filter>
      <inertia-link
        class="btn-indigo"
        :href="route('benefits.create')"
        v-if="$page.props.auth.organization.subscription.number_of_benefits - $page.props.auth.organization.benefits_with_trashed_count > 0"
      >
        <span>Create</span>
        <span class="hidden md:inline">Benefit</span>
      </inertia-link>
      <span v-else>You have reached your subscription limit</span>
    </div>
    <div class="overflow-x-auto bg-white rounded shadow-md">
      <table class="w-full whitespace-no-wrap">
        <tr class="font-bold text-left bg-gray-300">
          <th class="px-6 py-4">Title</th>
          <th class="px-6 py-4">Details</th>
          <th class="px-6 py-4">Promo Code</th>
          <th class="px-6 py-4">Redemption Link</th>
          <th class="px-6 py-4">Redemptions Count</th>
          <th
            class="px-6 py-4"
            colspan="2"
          >Created At</th>
        </tr>
        <tr
          v-for="benefit in benefits.data"
          :key="benefit.id"
          class="hover:bg-gray-100 focus-within:bg-gray-100"
        >
          <td class="border-t">
            <inertia-link
              class="flex items-center px-6 py-4"
              tabindex="-1"
              :href="route('benefits.edit',benefit.id)"
            >
              <img
                v-if="benefit.image"
                class="block w-5 h-5 mr-2 -my-2 rounded-full"
                :src="`/storage/images/${benefit.image}`"
              />
              {{ benefit.title }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link
              class="flex items-center px-6 py-4"
              tabindex="-1"
              :href="route('benefits.edit',benefit.id)"
            >{{ benefit.details }}</inertia-link>
          </td>
          <td class="border-t">
            <inertia-link
              class="flex items-center px-6 py-4"
              tabindex="-1"
              :href="route('benefits.edit',benefit.id)"
            >{{ benefit.promo_code }}</inertia-link>
          </td>
          <td class="border-t">
            <inertia-link
              class="flex items-center px-6 py-4"
              tabindex="-1"
              :href="route('benefits.edit',benefit.id)"
            >{{ benefit.redemption_link}}</inertia-link>
          </td>
          <td class="border-t">
            <inertia-link
              class="flex items-center px-6 py-4"
              tabindex="-1"
              :href="route('benefits.edit',benefit.id)"
            >{{ benefit.redemptions_count }}</inertia-link>
          </td>
          <td class="border-t">
            <span
              class="flex items-center px-6 py-4"
              tabindex="-1"
            >{{ moment(benefit.created_at).format('LLL') }}</span>
          </td>
          <td class="w-px border-t">
            <span
              class="flex items-center px-4"
              tabindex="-1"
            >
              <inertia-link
                class="mr-2"
                tabindex="-1"
                :href="route('benefits.edit',benefit.id)"
              >
                <icon
                  name="pencil"
                  class="block w-6 h-6 cursor-pointer fill-gray-500 hover:fill-gray-800"
                />
              </inertia-link>
              <button
                v-if="!benefit.deleted_at"
                tabindex="-1"
                type="button"
                @click="destroy(benefit.id)"
              >
                <icon
                  name="trash"
                  class="block w-6 h-6 cursor-pointer fill-gray-400 hover:fill-red-600"
                />
              </button>
            </span>
          </td>
        </tr>
        <tr v-if="benefits.data.length === 0">
          <td
            class="px-6 py-4 border-t"
            colspan="4"
          >No benefit found.</td>
        </tr>
      </table>
    </div>
    <pagination :links="benefits.links" />
  </div>
</template>

<script>
import Icon from "@/Shared/Icon";
import Layout from "@/Shared/Layout";
import mapValues from "lodash/mapValues";
import Pagination from "@/Shared/Pagination";
import pickBy from "lodash/pickBy";
import SearchFilter from "@/Shared/SearchFilter";
import debounce from "lodash/debounce";

export default {
  metaInfo: { title: "Benefits" },
  layout: Layout,
  components: {
    Icon,
    Pagination,
    SearchFilter,
  },
  props: {
    benefits: Object,
    filters: Object,
  },
  data () {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    };
  },
  watch: {
    form: {
      handler: debounce(function () {
        let query = pickBy(this.form);
        this.$inertia.replace(
          this.route(
            "organization.benefits",
            Object.keys(query).length ? query : { remember: "forget" }
          )
        );
      }, 400),
      deep: true,
    },
  },
  methods: {
    reset () {
      this.form = mapValues(this.form, () => null);
    },
    destroy (id) {
      if (confirm("Are you sure you want to delete this benefit?")) {
        this.$inertia.delete(this.route("benefits.destroy", id));
      }
    },
  },
};
</script>
