<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>TodoMVC</title>
    <script src="https://unpkg.com/vue"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('style.css'); ?>"/>
    <style>
      [v-cloak] {
        display: none;
      }
    </style>
  </head>
  <body>
    <section class="todoapp">
      <header class="header">
        <h1>todos</h1>
        <input
          class="new-todo"
          autofocus
          autocomplete="off"
          placeholder="What needs to be done?"
          v-model="newTodo"
          @keyup.enter="addTodo"
        />
      </header>
      <section class="main" v-show="todos.length" v-cloak>
        <input
          id="toggle-all"
          class="toggle-all"
          type="checkbox"
          v-model="allDone"
          @change="bulk_update"
          :true-value="1" 
          :false-value="0"
        />
        <label for="toggle-all"></label>
        <ul class="todo-list">
          <li
            v-for="todo in filteredTodos"
            class="todo"
            :key="todo.id"
            :class="{ completed: isCompleted(todo.completed), editing: todo == editedTodo }"
          >
            <div class="view">
              <input @change="updateTodo(todo)" class="toggle" type="checkbox" :true-value="1" :false-value="0" v-model="todo.completed" />
              <label @dblclick="editTodo(todo)">{{ todo.title }}</label>
              <button class="destroy" @click="removeTodo(todo)"></button>
            </div>
            <input
              class="edit"
              type="text"
              v-model="todo.title"
              v-todo-focus="todo == editedTodo"
              @blur="doneEdit(todo)"
              @keyup.enter="doneEdit(todo)"
              @keyup.esc="cancelEdit(todo)"
            />
          </li>
        </ul>
      </section>
      <footer class="footer" v-show="todos.length" v-cloak>
        <span class="todo-count">
          <strong>{{ remaining }}</strong> {{ remaining | pluralize }} left
        </span>
        <ul class="filters">
          <li>
            <a href="#/all" :class="{ selected: visibility == 'all' }">All</a>
          </li>
          <li>
            <a href="#/active" :class="{ selected: visibility == 'active' }"
              >Active</a
            >
          </li>
          <li>
            <a
              href="#/completed"
              :class="{ selected: visibility == 'completed' }"
              >Completed</a
            >
          </li>
        </ul>
        <button
          class="clear-completed"
          @click="removeCompleted"
          v-show="todos.length > remaining"
        >
          Clear completed
        </button>
      </footer>
    </section>
    

    <script>

      // visibility filters
      var filters = {
        all: function(todos) {
          return todos;
        },
        active: function(todos) {
          return todos.filter(function(todo) {
            return todo.completed != 1;
          });
        },
        completed: function(todos) {
          return todos.filter(function(todo) {
            return todo.completed == 1;
          });
        }
      };

      // app Vue instance
      var app = new Vue({
        // app initial state
        data: {
          todos: [],
          newTodo: "",
          editedTodo: null,
          visibility: "all",
          apiURL: "http://localhost:8080/"
        },
        created() {
          fetch(this.apiURL + 'todos')
          .then(res => res.json())
          .then(res => (this.todos = res))
          .catch(error => console.log(error));
        },
       
        // computed properties
        // http://vuejs.org/guide/computed.html
        computed: {
          filteredTodos: function() {
            return filters[this.visibility](this.todos);
          },
          remaining: function() {
            return filters.active(this.todos).length;
          },
          allDone: {
            get: function() {
              return this.remaining === 0;
            },
            set: function(value) {
              this.todos.forEach(function(todo) {
                todo.completed = value;
              });
            }
          }
        },

        filters: {
          pluralize: function(n) {
            return n === 1 ? "item" : "items";
          }
        },

        // methods that implement data logic.
        // note there's no DOM manipulation here at all.
        methods: {
          isCompleted: function(completed){
            return completed == 1;
          },
          addTodo: function() {
            var value = this.newTodo && this.newTodo.trim();
            if (!value) {
              return;
            }
            const requestOptions = {
              method: "POST",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify({ title: value })
            };
            fetch(this.apiURL + "todo/create", requestOptions)
            .then(res => {
              if (res.status < 500) {
                this.todos.push({
                  title: value,
                  completed: false
                });
                console.log('create res', res);
                console.log('op', requestOptions);
                
              
              }
            })
            this.newTodo = "";
          },

          removeTodo: function(todo) {
            fetch(this.apiURL + 'todo/' + todo.id, {
              body: JSON.stringify(todo),
              method: "DELETE",
              headers: {
                "Content-Type": "application/json",
              },
            })
            .then(res => {
              if (res.status < 500) {
                this.todos.splice(this.todos.indexOf(todo), 1);
                console.log('Delete response: ',res);
                  
              }
              else{
                console.log('Poor Delete: ',res);
              }
            })
          },

          editTodo: function(todo) {
            this.beforeEditCache = todo.title;
            this.editedTodo = todo;
          },

          updateTodo(todo) {
            fetch(this.apiURL + 'todo/' + todo.id, {
              body: JSON.stringify(todo),
              method: "PUT",
              headers: {
                "Content-Type": "application/json",
              },
            })
            .then(res => {
              if (res.status < 500) {
                this.todos.splice(this.todos.indexOf(todo), 1, todo)
                // this.todos.push({ id: this.todos.length + 1, title: value, completed: false })
                // this.newTodo = ''
                console.log(res);
                
              }
              else{
                console.log('trouble in paradise');
                
              }
            })
            // console.log('String', todo.completed);
            ;
          },
          bulk_update: function(){
            
            const bulkUpdateOptions = {
              method: "POST",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify({ todos: this.filteredTodos })
            };
            fetch(this.apiURL + "bulk/update", bulkUpdateOptions)
            .then(res => {
              if (res.status < 400) {
                console.log('Bulk Update successfull', res);
              }
              else{
                console.log('Bulk smash',res);
              }
            })
            
          },
          doneEdit: function(todo) {
            // this.updateTodo(todo);
            if (!this.editedTodo) {
              return;
            }
            this.editedTodo = null;
            todo.title = todo.title.trim();
            if (!todo.title) {
              this.removeTodo(todo);
            }
            else{
              this.updateTodo(todo);
            }
          },

          cancelEdit: function(todo) {
            this.editedTodo = null;
            todo.title = this.beforeEditCache;
          },

          removeCompleted: function() {
            fetch(this.apiURL + 'clear/completed')
            .then(res => res.json())
            .then(res => (this.todos = filters.active(this.todos)))
            .catch(error => console.log(error));
          }
        },

        // a custom directive to wait for the DOM to be updated
        // before focusing on the input field.
        directives: {
          "todo-focus": function(el, binding) {
            if (binding.value) {
              el.focus();
            }
          }
        }
      });

      // handle routing
      function onHashChange() {
        var visibility = window.location.hash.replace(/#\/?/, "");
        if (filters[visibility]) {
          app.visibility = visibility;
        } else {
          window.location.hash = "";
          app.visibility = "all";
        }
      }

      window.addEventListener("hashchange", onHashChange);
      onHashChange();

      // mount
      app.$mount(".todoapp");
    </script>
  </body>
</html>
