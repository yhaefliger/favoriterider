import Grid from '../../../../../../admin-dev/themes/new-theme/js/components/grid/grid';
import FiltersResetExtension
  from "../../../../../../admin-dev/themes/new-theme/js/components/grid/extension/filters-reset-extension";
import SortingExtension
  from "../../../../../../admin-dev/themes/new-theme/js/components/grid/extension/sorting-extension";
import ReloadListExtension
  from "../../../../../../admin-dev/themes/new-theme/js/components/grid/extension/reload-list-extension";

const $ = window.$;

$(document).ready(() => {
  const ridersGrid = new Grid('rider_entity');

  ridersGrid.addExtension(new FiltersResetExtension());
  ridersGrid.addExtension(new SortingExtension());
  ridersGrid.addExtension(new ReloadListExtension());
});