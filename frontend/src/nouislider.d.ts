/* eslint-disable */
declare module 'nouislider' {
  interface CssClasses {
    target: string;
    base: string;
    origin: string;
    handle: string;
    handleLower: string;
    handleUpper: string;
    touchArea: string;
    horizontal: string;
    vertical: string;
    background: string;
    connect: string;
    connects: string;
    ltr: string;
    rtl: string;
    textDirectionLtr: string;
    textDirectionRtl: string;
    draggable: string;
    drag: string;
    tap: string;
    active: string;
    tooltip: string;
    pips: string;
    pipsHorizontal: string;
    pipsVertical: string;
    marker: string;
    markerHorizontal: string;
    markerVertical: string;
    markerNormal: string;
    markerLarge: string;
    markerSub: string;
    value: string;
    valueHorizontal: string;
    valueVertical: string;
    valueNormal: string;
    valueLarge: string;
    valueSub: string;
  }

  export interface PartialFormatter {
    to: (value: number) => string | number;
    from?: (value: string) => number | false;
  }

  export interface Formatter extends PartialFormatter {
    from: (value: string) => number | false;
  }

  export enum PipsMode {
    Range = 'range',
    Steps = 'steps',
    Positions = 'positions',
    Count = 'count',
    Values = 'values'
  }

  export enum PipsType {
    None = -1,
    NoValue = 0,
    LargeValue = 1,
    SmallValue = 2
  }

  type WrappedSubRange = [number] | [number, number];

  type SubRange = number | WrappedSubRange;

  interface Range {
    min: SubRange;
    max: SubRange;

    [key: string]: SubRange;
  }

  interface BasePips {
    mode: PipsMode;
    density?: number;
    filter?: PipsFilter;
    format?: PartialFormatter;
  }

  export interface PositionsPips extends BasePips {
    mode: PipsMode.Positions;
    values: number[];
    stepped?: boolean;
  }

  export interface ValuesPips extends BasePips {
    mode: PipsMode.Values;
    values: number[];
    stepped?: boolean;
  }

  export interface CountPips extends BasePips {
    mode: PipsMode.Count;
    values: number;
    stepped?: boolean;
  }

  export interface StepsPips extends BasePips {
    mode: PipsMode.Steps;
  }

  export interface RangePips extends BasePips {
    mode: PipsMode.Range;
  }

  type Pips = PositionsPips | ValuesPips | CountPips | StepsPips | RangePips;

  type StartValues = string | number | (string | number)[];

  interface UpdatableOptions {
    range?: Range;
    start?: StartValues;
    margin?: number;
    limit?: number;
    padding?: number | number[];
    snap?: boolean;
    step?: number;
    pips?: Pips;
    format?: Formatter;
    tooltips?: boolean | PartialFormatter | (boolean | PartialFormatter)[];
    animate?: boolean;
  }

  export interface Options extends UpdatableOptions {
    range: Range;
    connect?: 'lower' | 'upper' | boolean | boolean[];
    orientation?: 'vertical' | 'horizontal';
    direction?: 'ltr' | 'rtl';
    behaviour?: string;
    keyboardSupport?: boolean;
    keyboardPageMultiplier?: number;
    keyboardMultiplier?: number;
    keyboardDefaultStep?: number;
    documentElement?: HTMLElement;
    cssPrefix?: string;
    cssClasses?: CssClasses;
    ariaFormat?: PartialFormatter;
    animationDuration?: number;
  }

  interface Behaviour {
    tap: boolean;
    drag: boolean;
    fixed: boolean;
    snap: boolean;
    hover: boolean;
    unconstrained: boolean;
  }

  interface ParsedOptions {
    animate: boolean;
    connect: boolean[];
    start: number[];
    margin: number[] | null;
    limit: number[] | null;
    padding: number[][] | null;
    step?: number;
    orientation?: 'vertical' | 'horizontal';
    direction?: 'ltr' | 'rtl';
    tooltips?: (boolean | PartialFormatter)[];
    keyboardSupport: boolean;
    keyboardPageMultiplier: number;
    keyboardMultiplier: number;
    keyboardDefaultStep: number;
    documentElement?: HTMLElement;
    cssPrefix?: string | false;
    cssClasses: CssClasses;
    ariaFormat: PartialFormatter;
    pips?: Pips;
    animationDuration: number;
    snap?: boolean;
    format: Formatter;

    range: Range;
    singleStep: number;
    transformRule: 'transform' | 'msTransform' | 'webkitTransform';
    style: 'left' | 'top' | 'right' | 'bottom';
    ort: 0 | 1;
    handles: number;
    events: Behaviour;
    dir: 0 | 1;
    spectrum: Spectrum;
  }

  export interface API {
    destroy: () => void;
    steps: () => NextStepsForHandle[];
    on: (eventName: string, callback: EventCallback) => void;
    off: (eventName: string) => void;
    get: (unencoded?: boolean) => GetResult;
    set: (input: number | string | (number | string)[], fireSetEvent?: boolean, exactInput?: boolean) => void;
    setHandle: (handleNumber: number, value: number | string, fireSetEvent?: boolean, exactInput?: boolean) => void;
    reset: (fireSetEvent?: boolean) => void;
    options: Options;
    updateOptions: (optionsToUpdate: UpdatableOptions, fireSetEvent: boolean) => void;
    target: HTMLElement;
    removePips: () => void;
    removeTooltips: () => void;
    getTooltips: () => { [handleNumber: number]: HTMLElement | false };
    getOrigins: () => { [handleNumber: number]: HTMLElement };
    pips: (grid: Pips) => HTMLElement;
  }

  interface TargetElement extends HTMLElement {
    noUiSlider?: API;
  }

  interface CSSStyleDeclarationIE10 extends CSSStyleDeclaration {
    msTransform?: string;
  }

  interface EventData {
    target?: HTMLElement;
    handles?: HTMLElement[];
    handle?: HTMLElement;
    connect?: HTMLElement;
    listeners?: [string, EventHandler][];
    startCalcPoint?: number;
    baseSize?: number;
    pageOffset?: PageOffset;
    handleNumbers: number[];
    buttonsProperty?: number;
    locations?: number[];
    doNotReject?: boolean;
    hover?: boolean;
  }

  interface MoveEventData extends EventData {
    listeners: [string, EventHandler][];
    startCalcPoint: number;
    baseSize: number;
    locations: number[];
  }

  interface EndEventData extends EventData {
    listeners: [string, EventHandler][];
  }

  interface NearByStep {
    startValue: number;
    step: number | false;
    highestStep: number;
  }

  interface NearBySteps {
    stepBefore: NearByStep;
    thisStep: NearByStep;
    stepAfter: NearByStep;
  }

  type EventHandler = (event: BrowserEvent) => false | undefined;

  type GetResult = number | string | (string | number)[];

  type NextStepsForHandle = [number | false | null, number | false | null];

  type OptionKey = (keyof Options) & (keyof ParsedOptions) & (keyof UpdatableOptions);

  type PipsFilter = (value: number, type: PipsType) => PipsType;

  type PageOffset = { x: number; y: number };

  type BrowserEvent = MouseEvent &
    TouchEvent & { pageOffset: PageOffset; points: [number, number]; cursor: boolean; calcPoint: number };

  type EventCallback = (
    this: API,
    values: (number | string)[],
    handleNumber: number,
    unencoded: number[],
    tap: boolean,
    locations: number[],
    slider: API
  ) => void;

  function initialize (target: TargetElement, originalOptions: Options): API

  export { TargetElement as target }

  export { initialize as create }

  export { cssClasses }

  export default {
    // Exposed for unit testing, don't use this in your application.
    __spectrum: Spectrum,
    // A reference to the default classes, allows global changes.
    // Use the cssClasses option for changes to one slider.
    cssClasses: cssClasses,
    create: initialize
  }
}
