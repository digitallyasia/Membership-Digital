<template>
  <div class="-m-12">
    <main>
      <div class="flex flex-col p-12 xl:flex-row">
        <div
          class="flex flex-col flex-wrap content-start w-full :w-1/2 xl:w-2/3 md:flex-row lg:mb-12 xl:mb-0"
        >
          <div class="w-full mb-4 lg:mb-0 md:w-1/2">
            <div class="justify-center p-5 text-center card sm:mr-4 lg:mr-12">
              <div class="flex flex-col text-center">
                <div class="mb-2 text-base font-bold">Your Current Organisation Plan</div>
                <h1
                  class="py-2 font-serif text-3xl font-semibold leading-none text-gray-900 sm:text-4xl lg:text-5xl"
                >{{$page.props.auth.organization.subscription.name}}</h1>
              </div>
            </div>
          </div>
          <div class="w-full mb-4 lg:mb-0 md:w-1/2">
            <div class="justify-start p-5 card xl:mr-12">
              <div class="flex flex-col w-full">
                <div class="mb-1 text-base font-bold text-center">Plan Detail</div>
                <div>
                  Members:
                  <span
                    class="font-serif font-semibold"
                  >{{ $page.props.auth.organization.subscription.number_of_members}}</span>
                  <span>(total)</span>
                </div>
                <div>
                  Notifications:
                  <span
                    class="font-serif font-semibold"
                  >{{ $page.props.auth.organization.subscription.number_of_notifications}}</span>
                  <span>(monthly)</span>
                </div>
                <div>
                  Announcements:
                  <span
                    class="font-serif font-semibold"
                  >{{ $page.props.auth.organization.subscription.number_of_announcements}}</span>
                  <span>(monthly)</span>
                </div>
                <div>
                  Benefits:
                  <span
                    class="font-serif font-semibold"
                  >{{ $page.props.auth.organization.subscription.number_of_benefits}}</span>
                  <span>(total)</span>
                </div>
              </div>
            </div>
          </div>
          <div class="w-full mb-4 lg:mb-0" v-if="pendingPayment">
            <div class="items-center justify-start mr-4 card sm:mr-4 lg:mr-12">
              <div class="flex flex-col">
                <div class="mb-2 font-serif text-xl font-bold text-gray-900">You have a bill due</div>
                <div>
                  Plan:
                  <span
                    class="font-serif font-semibold"
                  >{{ plans.find(({ id }) => id === pendingPayment.plan_id).name}}</span>
                </div>
                <div>
                  Price:
                  <span class="font-serif font-semibold">{{ pendingPayment.amount}}</span>
                </div>
              </div>
              <a
                class="flex items-center justify-center ml-auto font-bold text-center rounded-lg btn-indigo"
                :href="`https://www.billplz.com/bills/${pendingPayment.bill_id}`"
                target="_blank"
              >Pay Now</a>
              <button
                :disabled="canceling"
                class="flex items-center justify-center ml-2 font-bold text-center rounded-lg btn-indigo"
                @click="cancelUpgrade"
              >
                <div v-if="canceling" class="mr-2 btn-spinner" />Cancel Upgrade
              </button>
            </div>
          </div>
        </div>
        <div class="w-full xl:w-1/2">
          <div class="w-full overflow-x-auto bg-white rounded shadow-md">
            <table class="w-full whitespace-no-wrap">
              <tr class="font-bold text-left bg-gray-300">
                <th class="px-6 py-3">Plan</th>
                <th class="px-6 py-3">Subscription</th>
                <th class="px-6 py-3">Amount</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Paid At</th>
              </tr>
              <tr
                v-for="payment in payments.data"
                :key="payment.id"
                class="hover:bg-gray-100 focus-within:bg-gray-100"
              >
                <td class="border-t">
                  <span class="flex items-center px-6 py-4" tabindex="-1">{{ payment.plan.name }}</span>
                </td>
                <td class="border-t">
                  <span
                    class="flex items-center px-6 py-4 capitalize"
                    tabindex="-1"
                  >{{ payment.subscription }}</span>
                </td>
                <td class="border-t">
                  <span class="flex items-center px-6 py-4" tabindex="-1">{{ payment.amount }}</span>
                </td>
                <td class="border-t">
                  <span class="flex items-center px-4" tabindex="-1">
                    <span
                      v-if="payment.state === 'paid'"
                      class="px-4 py-2 font-bold text-green-500 bg-green-200 rounded-full"
                    >Paid</span>
                    <span
                      v-if="payment.state === 'due'"
                      class="px-4 py-2 font-bold text-red-500 bg-red-200 rounded-full"
                    >Due</span>
                  </span>
                </td>
                <td class="border-t">
                  <span
                    class="flex items-center px-6 py-4"
                    tabindex="-1"
                  >{{ moment(payment.paid_at).format('LLL') }}</span>
                </td>
              </tr>
              <tr v-if="payments.data.length === 0">
                <td class="px-6 py-4 text-center border-t" colspan="5">No history of payments</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div
        class="items-center w-full p-12 pt-0 xl:w-1/2"
        v-if="!showPackages && !pendingPayment && $page.props.auth.organization.subscription.id ===1"
      >
        <div class="flex-col items-center text-center card md:flex-row md:text-left">
          <div class="flex flex-col">
            <div class="text-xl font-bold">Would you like to upgrade your package?</div>
          </div>
          <button
            class="mt-4 font-bold text-center rounded-lg btn-indigo md:mt-0"
            @click="showPackages = true"
          >Upgrade Package</button>
        </div>
      </div>

      <div class="pb-12 pl-12 sm:mr-4 lg:mr-12" v-if="showPackages">
        <div class="px-4 pt-12 bg-gray-900 sm:px-6 lg:px-8 rounded-t-md">
          <div class="text-center">
            <p
              class="max-w-xl mx-auto mt-2 text-xl leading-7 text-gray-300 lg:max-w-4xl lg:text-2xl lg:leading-8"
            >Automating your organisation is cheaper than hiring an admin executive.</p>
            <div class="mt-8">
              <button
                class="c-toggle"
                :class="{'c-toggle--active':isYearly}"
                @click="changeSubscription"
              >
                <div class="c-toggle__handle"></div>
                <span class="c-toggle__label">{{isYearly ? 'Yearly':'Monthly'}}</span>
              </button>
            </div>
          </div>
        </div>
        <div class="flex justify-center p-12 pt-8 bg-gray-900">
          <div class="plans">
            <div
              :class="'mycard flex  flex-col '+(plan.code)"
              v-for="plan in plans"
              :key="plan.name"
            >
              <div class="font-serif font-semibold title">
                <h2>{{plan.name}}</h2>
              </div>
              <div class="flex items-baseline justify-center price">
                <h4>
                  <span class="text-3xl">RM</span>
                  {{isYearly? plan.yearly_price:plan.monthly_price}}
                </h4>
                <span class="text-white">{{isYearly?'Yearly':'Monthly'}}</span>
              </div>
              <div class="option">
                <ul>
                  <!-- <li v-for="detail in plan.details" :key="detail">{{detail}}</li> -->
                  <li v-if="plan.card_customization">Card Customization</li>
                  <li v-if="plan.membership_number">Membership Number</li>
                  <li v-if="plan.import_membership_csv">Import Membership CSV</li>
                  <li>Up to {{plan.number_of_benefits}} Benefits</li>
                  <li>Up to {{plan.number_of_announcements}} Announcement</li>
                  <li>Up to {{plan.number_of_notifications}} Push Notifications</li>
                  <li>Up to {{plan.number_of_members}} Total Members</li>
                </ul>
              </div>
              <div class="mt-auto">
                <div class="mt-2 rounded-lg shadow-md">
                  <button
                    class="block w-full px-6 py-4 text-base font-semibold leading-6 text-center text-teal-600 transition duration-150 ease-in-out bg-white rounded-lg cursor-pointer font-display hover:text-teal-500 focus:outline-none focus:shadow-outline"
                    @click="changePlan(plan)"
                    v-text="plan.id === $page.props.auth.organization.plan_id ? 'Current Plan': selectedPlan && selectedPlan.id === plan.id?'Selected Plan': 'Change Plan'"
                  ></button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="text-center bg-gray-900 rounded-b-md" v-if="selectedPlan">
          <h2
            class="font-serif text-3xl font-semibold leading-none text-white sm:text-4xl lg:text-5xl"
            v-text="selectedPlan.name"
          ></h2>
          <div class="flex items-baseline justify-center mt-4">
            <span
              class="pr-1 font-serif text-2xl font-semibold leading-none text-white sm:text-5xl lg:text-3xl"
            >RM</span>
            <h1
              class="font-serif text-4xl font-semibold leading-none text-white sm:text-5xl lg:text-6xl"
            >{{isYearly? selectedPlan.yearly_price:selectedPlan.monthly_price}}</h1>
            <span
              class="pl-1 font-serif text-2xl font-semibold leading-none text-white sm:text-5xl lg:text-3xl"
            >
              <span class="text-white">{{isYearly?'Yearly':'Monthly'}}</span>
            </span>
          </div>
          <button
            :disabled="generatingBill"
            class="inline-flex items-center justify-center w-56 px-6 py-4 mt-3 mb-10 text-2xl font-bold text-center text-black text-gray-900 bg-white rounded-lg rounded-full btn-indigo focus:shadow-outline"
            @click="generateBill"
          >
            <div v-if="generatingBill" class="mr-2 btn-spinner" />Upgrade
          </button>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import Icon from "@/Shared/Icon";
import Layout from "@/Shared/Layout";
import mapValues from "lodash/mapValues";
import Pagination from "@/Shared/Pagination";
import pickBy from "lodash/pickBy";
import debounce from "lodash/debounce";
export default {
  metaInfo: { title: "Members" },
  layout: Layout,
  components: {
    Icon,
    Pagination,
  },
  props: {
    members: Object,
    payments: Object,
    plans: Array,
    pendingPayment: Object,
  },
  data() {
    return {
      isYearly: true,
      activeTab: "Accepted",
      showPackages: false,
      selectedPlan: null,
      selectedSubscription: "monthly",
      canceling: false,
      generatingBill: false,
    };
  },
  methods: {
    changeSubscription() {
      this.isYearly = !this.isYearly;
      this.selectedSubscription = this.isYearly ? "yearly" : "monthly";
    },
    changePlan(plan) {
      if (plan.id !== 1) {
        this.selectedPlan = plan;
      } else {
        this.selectedPlan = null;
      }
    },
    cancelUpgrade() {
      this.canceling = true;
      this.$inertia.post(
        this.route("organization.bill.delete"),
        {
          bill_id: this.pendingPayment.bill_id,
        },
        {
          onFinish: () => {
            this.canceling = false;
          },
        }
      );
    },
    generateBill() {
      this.generatingBill = true;
      this.$inertia.post(
        this.route("organization.bill.generate"),
        {
          plan_id: this.selectedPlan.id,
          subscription: this.selectedSubscription,
        },
        {
          onFinish: () => {
            this.generatingBill = false;
            this.showPackages = false;
            this.selectedPlan = null;
          },
        }
      );
    },
  },
};
</script>
<style scoped>
body {
  background: #262626;
  box-sizing: border-box;
  font-family: "Roboto", sans-serif !important;
  height: 100vh;
  display: flex;
  justify-content: center;
}
</style>