import Grid from '@js/components/grid/grid';
import FiltersResetExtension from "@js/components/grid/extension/filters-reset-extension";
import SortingExtension from "@js/components/grid/extension/sorting-extension";
import ReloadListExtension from "@js/components/grid/extension/reload-list-extension";

const $ = window.$;

$(document).ready(() => {
  const ridersGrid = new Grid('rider_entity');

  ridersGrid.addExtension(new FiltersResetExtension());
  ridersGrid.addExtension(new SortingExtension());
  ridersGrid.addExtension(new ReloadListExtension());
});