/** Pulse API types — den 14 */
export interface PulseEvent {
  stage: string;
  message: string;
  at: string;
}

export interface WorkOrderDto {
  id: number;
  title: string;
  type: string;
  status: 'queued' | 'running' | 'done' | 'failed';
  currentStage: string | null;
  createdAt: string;
  finishedAt: string | null;
  events: PulseEvent[];
}

export interface PulseListResponse {
  orders: WorkOrderDto[];
  stats: {
    queued: number;
    running: number;
    done: number;
    failed: number;
    total: number;
  };
}
