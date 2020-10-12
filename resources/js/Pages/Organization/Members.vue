<template>
  <div>
    <h1 class="mb-8 text-3xl font-bold">Members</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset"></search-filter>
    </div>
    <div class="pt-2 bg-white rounded-lg shadow-lg">
      <div class="flex flex-row ml-auto">
        <ul class="flex w-full list-none border-b-4 border-gray-300" role="tablist">
          <li
            class="px-4 py-2 pb-4 bg-white"
            :class="{ 'border-b-4 border-gray-600': activeTab==='Accepted' }"
            :style="activeTab==='Accepted' ? 'margin-bottom: -4px' : ''"
          >
            <inertia-link
              :href="route('organization.members.active')"
              :class="{ 'font-bold': activeTab==='Accepted' }"
              class="p-1 text-xl focus:outline-none"
              role="tab"
              :aria-selected="activeTab==='Accepted'"
            >Accepted</inertia-link>
          </li>
          <li
            class="px-4 py-2 pb-4 bg-white"
            :class="{ 'border-b-4 border-gray-600': activeTab==='Blocked' }"
            :style="activeTab==='Blocked' ? 'margin-bottom: -4px' : ''"
          >
            <inertia-link
              :href="route('organization.members.blocked')"
              :class="{ 'font-bold': activeTab==='Blocked' }"
              class="p-1 text-xl focus:outline-none"
              role="tab"
              :aria-selected="activeTab==='Blocked'"
            >Blocked</inertia-link>
          </li>
          <li
            v-if="!$page.props.auth.organization.auto_join"
            class="px-4 py-2 pb-4 bg-white"
            :class="{ 'border-b-4 border-gray-600': activeTab==='Pending' }"
            :style="activeTab==='Pending' ? 'margin-bottom: -4px' : ''"
          >
            <inertia-link
              :href="route('organization.members.pending')"
              :class="{ 'font-bold': activeTab==='Pending' }"
              class="p-1 text-xl focus:outline-none"
              role="tab"
              :aria-selected="activeTab==='Pending'"
            >Pending</inertia-link>
          </li>
          <div class="pb-2 ml-auto mr-2">
            <div class="flex items-center justify-center p-3 bg-gray-200 rounded-full">
              <label for="toogleA" class="flex items-center cursor-pointer">
                <div class="mr-3 font-medium text-gray-700">Auto Join</div>
                <div class="relative">
                  <input
                    id="toogleA"
                    type="checkbox"
                    class="hidden"
                    v-model="auto_join"
                    @click.prevent="toggleAutoJoin"
                  />
                  <div class="w-10 h-4 bg-gray-400 rounded-full shadow-inner toggle__line"></div>
                  <div
                    class="absolute inset-y-0 left-0 w-6 h-6 bg-white rounded-full shadow toggle__dot"
                  ></div>
                </div>
              </label>
            </div>
          </div>
        </ul>
      </div>
      <div class="m-4 overflow-x-auto bg-white rounded shadow-md">
        <table class="w-full whitespace-no-wrap">
          <tr class="font-bold text-left bg-gray-300">
            <th class="px-6 py-4">Name</th>
            <th class="px-6 py-4">‏Membership ID</th>
            <th class="px-6 py-4">Email</th>
            <th class="px-6 py-4">Phone Number</th>
            <th class="px-6 py-4">Date of Birth</th>
            <th class="px-6 py-4">Joined At</th>
            <th class="px-6 py-4" colspan="2">Address</th>
          </tr>
          <tr
            v-for="member in members.data"
            :key="member.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t">
              <span class="flex items-center px-6 py-4 focus:text-indigo-500">
                <img
                  v-if="member.profile_picture"
                  class="block w-5 h-5 mr-2 -my-2 rounded-full"
                  :src="`/storage/images/${member.profile_picture}`"
                />
                {{ member.name }}
              </span>
            </td>
            <td class="border-t">
              <span
                class="flex items-center px-6 py-4"
                tabindex="-1"
              >{{ padNum(member.pivot.membership_id) }}</span>
            </td>
            <td class="border-t">
              <span class="flex items-center px-6 py-4" tabindex="-1">{{ member.email }}</span>
            </td>
            <td class="border-t">
              <span class="flex items-center px-6 py-4" tabindex="-1">{{ member.phone_number }}</span>
            </td>
            <td class="border-t">
              <span class="flex items-center px-6 py-4" tabindex="-1">{{ member.dob }}</span>
            </td>
            <td class="border-t">
              <span
                class="flex items-center px-6 py-4"
                tabindex="-1"
              >{{ moment(member.pivot.created_at).format('LLL') }}</span>
            </td>
            <td class="border-t">
              <span class="flex items-center px-6 py-4" tabindex="-1">{{ member.address }}</span>
            </td>
            <td class="w-px border-t">
              <span class="flex items-center px-4" tabindex="-1">
                <button
                  v-if="activeTab==='Accepted'"
                  class="mr-2"
                  tabindex="-1"
                  title="Block Member"
                  @click="block(member.id)"
                >
                  <icon
                    name="block"
                    class="block w-6 h-6 cursor-pointer fill-gray-500 hover:fill-gray-800"
                  />
                </button>
                <button
                  v-if="activeTab==='Blocked'"
                  class="mr-2"
                  tabindex="-1"
                  title="Unblock Member"
                  @click="unblock(member.id)"
                >
                  <icon
                    name="un-block"
                    class="block w-6 h-6 cursor-pointer fill-gray-500 hover:fill-gray-800"
                  />
                </button>
                <button
                  v-if="activeTab==='Pending'"
                  class="mr-2"
                  tabindex="-1"
                  title="Accept Join Request"
                  @click="acceptJoinRequest(member.id)"
                >
                  <icon
                    name="un-block"
                    class="block w-6 h-6 cursor-pointer fill-gray-500 hover:fill-gray-800"
                  />
                </button>
                <button
                  tabindex="-1"
                  type="button"
                  title="Delete Member"
                  @click="deleteMember(member.id)"
                >
                  <icon
                    name="trash"
                    class="block w-6 h-6 cursor-pointer fill-gray-500 hover:fill-red-600"
                  />
                </button>
              </span>
            </td>
          </tr>
          <tr v-if="members.data.length === 0">
            <td class="px-6 py-4 border-t" colspan="4">No members found.</td>
          </tr>
        </table>
      </div>
      <pagination
        :links="members.links"
        class="flex flex-wrap p-2 pt-3 mt-2 -mb-1 bg-gray-200 rounded-b-lg"
      />
    </div>
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
  metaInfo: { title: "Members" },
  layout: Layout,
  components: {
    Icon,
    Pagination,
    SearchFilter,
  },
  props: {
    members: Object,
    filters: Object,
    tab: String,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    };
  },
  computed: {
    auto_join() {
      return this.$page.props.auth.organization.auto_join;
    },
    activeTab() {
      return this.tab;
    },
  },
  watch: {
    form: {
      handler: debounce(function () {
        let query = pickBy(this.form);
        this.$inertia.replace(
          this.route(
            "organization.members",
            Object.keys(query).length ? query : { remember: "forget" }
          )
        );
      }, 400),
      deep: true,
    },
  },
  methods: {
    toggleAutoJoin() {
      this.$inertia.post(
        this.route(
          "organization.toggle_auto_join",
          this.$page.props.auth.organization.id
        )
      );
    },
    reset() {
      this.form = mapValues(this.form, () => null);
    },
    block(id) {
      this.$inertia.post(this.route("organization.members.block"), {
        member_id: id,
      });
    },
    unblock(id) {
      this.$inertia.post(this.route("organization.members.unblock"), {
        member_id: id,
      });
    },
    acceptJoinRequest(id) {
      this.$inertia.post(this.route("organization.members.accept"), {
        member_id: id,
      });
    },
    deleteMember(id) {
      this.$inertia.post(this.route("organization.members.delete"), {
        member_id: id,
      });
    },
    padNum(num) {
      return (
        this.$page.props.auth.organization.name.substring(0, 3).toUpperCase() +
        Array(7 - num.toString().length).join("0") +
        num
      );
    },
  },
};
</script>