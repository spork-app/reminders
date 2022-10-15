export default {
  state: {},
  mutations: {},
  getters: {
    reminders(getters, rootGetters) {
      return rootGetters?.features?.reminders ?? [];
    },
    reminderEvents(getters, rootGetters) {
      return Object.values(rootGetters?.features?.reminders?.reduce((reminders, reminder) => {
        return {
          ...reminders,
          ...(reminder.repeatable ?? []),
        }
      }, {}) ?? {});
    },
  },
  actions: {
    async fetchReminders({ commit, rootGetters }) {
    },
    async createReminder({ dispatch, commit, rootGetters }, reminder) {
      if (!rootGetters?.features?.reminders) { 
        await dispatch('createFeature', {
          name: 'Reminders',
          feature: 'reminders',
          settings: {
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
          }
        })
      }

      await axios.post('/api/reminders/events', {
        ...reminder,
        date_start: dayjs(reminder.date_start),
        ...(reminder.date_end ? { date_end: dayjs(reminder.date_end) } : {}),
        feature_id: rootGetters?.features?.reminders?.[0]?.id,
      });

      await dispatch('getFeatureLists', {
        include: ['accounts', 'repeatable.users.user'],
      });
    },
    async deleteReminder({ dispatch, commit, rootGetters }, reminderEvent) {
      try {
        await axios.delete(`/api/reminders/events/${reminderEvent.id}`);
        await dispatch('getFeatureLists', {
          include: ['accounts', 'repeatable.users.user'],
          
        });
      } catch (e) {
        Spork.toast(e.message, 'error')
        throw e;
      }
    },
  },
}
