// import PostsView from './views/Posts';
import ToastsView from './views/Toasts';


export default function IndexController(container) {
  this._container = container;
  this._toastsView = new ToastsView(this._container);
  this._lostConnectionToast = null;
  this._registerServiceWorker();

  var indexController = this;
}

IndexController.prototype._registerServiceWorker = function() {
  if (!navigator.serviceWorker) return;

  var indexController = this;

  navigator.serviceWorker.register('/sw.js').then(function(reg) {
    if (!navigator.serviceWorker.controller) {
      return;
    }

    if (reg.waiting) {
      indexController._updateReady(reg.waiting);
      return;
    }

    if (reg.installing) {
      indexController._trackInstalling(reg.installing);
      return;
    }

    reg.addEventListener('updatefound', function() {
      indexController._trackInstalling(reg.installing);
    });

    navigator.serviceWorker.addEventListener('message', event => {
      indexController._sendMessage(event.data.msg);
    });    
  });

  // Ensure refresh is only called once.
  // This works around a bug in "force update on reload".
  var refreshing;
  navigator.serviceWorker.addEventListener('controllerchange', function() {
    if (refreshing) return;
    window.location.reload();
    refreshing = true;
  });
};

IndexController.prototype._sendMessage = function(message) {
 var indexController = this;
 if (!indexController._lostConnectionToast) {
   var toast = indexController._toastsView.show(message, {
      buttons: ['dismiss']
    });
    indexController._lostConnectionToast = 1;
    toast.answer.then(function(answer) {
      indexController._lostConnectionToast = null;
      return;
    });  
  }
}


IndexController.prototype._trackInstalling = function(worker) {
  var indexController = this;
  worker.addEventListener('statechange', function() {
    if (worker.state == 'installed') {
      indexController._updateReady(worker);
    }
  });
};

IndexController.prototype._updateReady = function(worker) {
  var toast = this._toastsView.show("New version available", {
    buttons: ['refresh', 'dismiss']
  });

  toast.answer.then(function(answer) {
    if (answer != 'refresh') return;
    worker.postMessage({action: 'skipWaiting'});
  });
};