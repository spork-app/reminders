Spork.setupStore({
    Reminders: require("./store").default,
})


Spork.routesFor('reminders', [
  Spork.authenticatedRoute('/reminders', require('./Reminders').default)
]);
